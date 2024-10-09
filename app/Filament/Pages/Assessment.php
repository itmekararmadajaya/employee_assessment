<?php

namespace App\Filament\Pages;

use App\Models\Assessor;
use App\Models\Departement;
use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessment;
use App\Models\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Assessment extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessment';

    public $user, $user_assessor_data, $assessment, $section, $employee_assessed, $assessment_data = [], $count_must_be_approve = 0, $user_approver_data;

    //Table
    public $section_id, $position;

    //Url Params
    public $status;

    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public function mount(){
        $user = Auth::user();
        if(!$user && !$user->hasRole('assessor')){
            abort(403, 'Not Authorized');
        }

        $this->user = $user;

        $slug = request('assessment');
        $this->assessment = EmployeeAssessment::where('slug', $slug)->first();
        abort_unless($this->assessment, 403, 'Assessment Not Found');

        /**
         * Get user assessor data
         */
        $this->user_assessor_data = Assessor::whereIn('assessor', [$user->nik])->get();
        $this->section_id = $this->user_assessor_data->pluck('section_id')->toArray();
        $this->position = array_unique(array_merge(...$this->user_assessor_data->pluck('assessed')->toArray()));

        /**
         * Count assessment data
         */
        $assessment_id = $this->assessment->id;
        $get_assessment_data = Employee::with(['assessments' => function($query) use($assessment_id){
            return $query->where('employee_assessment_id', $assessment_id);
        }])->whereIn('section_id', $this->section_id)->whereIn('position', $this->position)->get();

        $count_blank = $get_assessment_data->filter(function($employee) {
            return $employee->assessments->isEmpty();
        })->count();        
        $count_not_assessed = $get_assessment_data->filter(function($employee){
            return $employee->assessments->contains('status', 'not_assessed');
        })->count();
        $count_on_progress = $get_assessment_data->filter(function($employee){
            return $employee->assessments->contains('status', 'on_progress');
        })->count();
        $count_done = $get_assessment_data->filter(function($employee){
            return $employee->assessments->contains('status', 'done');
        })->count();
        $count_approved = $get_assessment_data->filter(function($employee){
            return $employee->assessments->contains('status', 'approved');
        })->count();
        $count_rejected = $get_assessment_data->filter(function($employee){
            return $employee->assessments->contains('status', 'rejected');
        })->count();

        $this->assessment_data = [
            'not_assessed' => $count_not_assessed + $count_blank,
            'on_progress' => $count_on_progress,
            'done' => $count_done,
            'approved' => $count_approved,
            'rejected' => $count_rejected
        ];

        $this->user_approver_data = Assessor::whereIn('approver', [$user->nik])->get();
        $user_assessor_nik_for_approve = array_unique($this->user_approver_data->pluck('assessor')->toArray());

        $this->count_must_be_approve = EmployeeAssessed::query()->where('employee_assessment_id', $this->assessment->id)->whereIn('assessor_nik', $user_assessor_nik_for_approve)->where(function ($query) {
            $query->where('status', 'done');
        })->count();

        /**
         * Filter by status
         */
         $this->status = request('status');
    }

    public function table(Table $table){
        $assessment_id = $this->assessment->id;
        $status = $this->status;
        if($status != null && $status != 'not_assessed'){
            $table_data = $table->query(EmployeeAssessed::query()->where('employee_assessment_id', $this->assessment->id)->where('assessor_id', $this->user->employee->id)->where('status', $status))
            ->recordClasses(fn (EmployeeAssessed $record) => match ($record->status){
                'not_assessed' => 'bg-white',
                'on_progress' => 'bg-blue-100',
                'done' => 'bg-yellow-100',
                'rejected' => 'bg-red-100',
                'approved' => 'bg-green-100',
                default => 'bg-gray-50'
            })
            ->columns([
                TextColumn::make('employee_nik')->searchable()->label('NIK'),
                TextColumn::make('employee_name')->searchable()->label('Name'),
                TextColumn::make('employee_position')->searchable()->label('Position'),
                TextColumn::make('employee_section')->searchable()->label('Section'),
                TextColumn::make('employee_departement')->searchable()->toggleable(true)->label('Departement'),
                TextColumn::make('status')->searchable(),
            ])->filters([
                SelectFilter::make('employee_section')
                    ->options(Section::get()->pluck('name', 'name'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Section'),
                SelectFilter::make('employee_departement')
                    ->options(Departement::get()->pluck('name', 'name'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label('Departement')
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Action::make('detail')
                ->url(fn (EmployeeAssessed $record): string => route('filament.admin.pages.assessment-detail', ['assessment' => $this->assessment->slug, 'employee' => Crypt::encrypt($record->employee->id)]))
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                // ...
            ]);
        }else{
            $table_data = $table
            ->query(Employee::query()
                ->where(function($query) use($assessment_id) {
                    $query->whereHas('assessments', function($query) use($assessment_id) {
                        $query->where('employee_assessment_id', $assessment_id)
                            ->where('status', 'not_assessed');
                    })
                    ->orDoesntHave('assessments');
                })
                ->whereIn('section_id', $this->section_id)
                ->whereIn('position', $this->position)        
            )
            ->recordClasses(fn (Employee $record) => match (optional($record->assessments->first())->status){
                'not_assessed' => 'bg-white',
                'done' => 'bg-yellow-100',
                'rejected' => 'bg-red-100',
                'approved' => 'bg-green-100',
                default => 'bg-gray-50'
            })
            ->columns([
                TextColumn::make('nik')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('position')->searchable(),
                TextColumn::make('section.name')->searchable(),
                TextColumn::make('section.departement.name')->searchable(),
                TextColumn::make('assessment_status')->label('status')->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('section')
                    ->relationship('section', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('departement')
                    ->relationship('section.departement', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
            ])
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Action::make('detail')
                ->url(fn (Employee $record): string => route('filament.admin.pages.assessment-detail', ['assessment' => $this->assessment->slug, 'employee' => Crypt::encrypt($record->id)]))
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                // ...
            ]);
        }

        return $table_data;
    }

    public function back(){
        return redirect()->route('filament.admin.pages.list-assessment');
    }
}
