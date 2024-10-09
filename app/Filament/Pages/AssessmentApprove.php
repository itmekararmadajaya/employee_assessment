<?php

namespace App\Filament\Pages;

use App\Models\Assessor;
use App\Models\Departement;
use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessment;
use App\Models\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AssessmentApprove extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessment-approve';
    
    public $user, $assessment, $user_approver_data, $user_assessor_data, $section_id, $position, $employee_assessed_id, $assessment_data;
    
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
         * Get user approver data (User Ini)
         */
        $this->user_approver_data = Assessor::whereIn('approver', [$user->nik])->get();
        $this->user_assessor_data = array_unique($this->user_approver_data->pluck('assessor')->toArray());
        

        /**
         * Get employee assessed must be approve
         */
        $employee_must_be_approve = EmployeeAssessed::where('employee_assessment_id', $this->assessment->id)->whereIn('assessor_nik', $this->user_assessor_data)->get();
        $this->assessment_data = [
            'done' => $employee_must_be_approve->where('status' ,'done')->count(),
            'approved' => $employee_must_be_approve->where('status' ,'approved')->count(),
            'rejected' => $employee_must_be_approve->where('status' ,'rejected')->count(),
        ];

        /**
         * Filter by status
         */
        $this->status = request('status') ? request('status') : 'done';
    }

    public function table(Table $table){
        $status = $this->status;
        if($status != null){
            $query = EmployeeAssessed::query()->where('employee_assessment_id', $this->assessment->id)->whereIn('assessor_nik', $this->user_assessor_data)->where(function ($query) use($status) {
                $query->where('status', '!=', 'not_assessed')
                      ->where('status', '!=', 'on_progress')
                      ->where('status', $status);
            });
        }else{
            $query = EmployeeAssessed::query()->where('employee_assessment_id', $this->assessment->id)->whereIn('assessor_nik', $this->user_assessor_data)->where(function ($query) {
                $query->where('status', '!=', 'not_assessed')
                      ->where('status', '!=', 'on_progress');
            });
        }
        return $table
                ->query($query)
                ->recordClasses(fn (EmployeeAssessed $record) => match ($record->status){
                    'done' => 'bg-yellow-100',
                    'rejected' => 'bg-red-100',
                    'approved' => 'bg-green-100',
                })
                ->columns([
                    TextColumn::make('assessment_date')->dateTime()->searchable()->toggleable(true),
                    TextColumn::make('employee_nik')->searchable(),
                    TextColumn::make('employee_name')->searchable(),
                    TextColumn::make('employee_position')->searchable(),
                    TextColumn::make('employee_section')->searchable(),
                    TextColumn::make('employee_departement')->searchable()->toggleable(true),
                    TextColumn::make('assessor_name')->searchable(),
                    TextColumn::make('approver_name')->searchable(),
                    TextColumn::make('status')->searchable(),
                ])
                ->filters([
                    SelectFilter::make('employee_section')
                        ->options(Section::get()->pluck('name', 'name'))
                        ->preload()
                        ->searchable(),
                    SelectFilter::make('employee_departement')
                        ->options(Departement::get()->pluck('name', 'name'))
                        ->preload()
                        ->searchable()
                ])
                ->filtersTriggerAction(
                    fn (Action $action) => $action
                        ->button()
                        ->label('Filter'),
                )
                ->actions([
                    Action::make('detail')
                    ->url(fn (EmployeeAssessed $record): string => route('filament.admin.pages.assessment-approve-detail', ['employee-assessed' => Crypt::encrypt($record->id)]))
                ], position: ActionsPosition::BeforeCells)
                ->bulkActions([
                    // ...
                ]);
    }

    public function back(){
        return redirect()->route('filament.admin.pages.assessment', ['assessment' => $this->assessment->slug]);
    }
}
