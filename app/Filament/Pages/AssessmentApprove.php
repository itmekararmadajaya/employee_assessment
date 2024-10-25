<?php

namespace App\Filament\Pages;

use App\Models\Assessor;
use App\Models\Departement;
use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessment;
use App\Models\Position;
use App\Models\Section;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class AssessmentApprove extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessment-approve';
    
    public $user, $assessment, $user_approver_data, $user_assessor_data, $section_id, $position, $employee_assessed_id, $assessment_data;
    
    //Url Params
    public $status;

    //Page Params
    public $page = 'AssessmentApprove';
    public $showModalApprove = false;
    public $get_id_employee_assessed_for_approve;

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

        if(Session::get('approve-status') == 'success'){
            Notification::make()->title('Success Approve')->success()->send();
            Session::forget('approve-status');
        }
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
                ->columns([
                    Split::make([
                        Stack::make([
                            ViewColumn::make('custom')->view('filament.table-template.employee-assessment')
                        ])
                    ])
                ])
                ->filters([
                    Filter::make('employee_name')
                        ->form([
                            TextInput::make('employee_name')
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query->where('employee_name', 'like', '%'.$data['employee_name'].'%');
                        }),
                    SelectFilter::make('employee_section')
                        ->options(Section::get()->pluck('name', 'name'))
                        ->preload()
                        ->label('Section')
                        ->searchable(),
                    SelectFilter::make('employee_departement')
                        ->label('Departement')
                        ->options(Departement::get()->pluck('name', 'name'))
                        ->preload()
                        ->searchable(),
                    SelectFilter::make('employee_position')->options(Position::get()->pluck('name', 'name'))->multiple()->label('Position')
                ], layout: FiltersLayout::AboveContent)
                ->filtersTriggerAction(
                    fn (Action $action) => $action
                        ->button()
                        ->label('Filter'),
                )
                ->actions([
                ], position: ActionsPosition::BeforeCells)
                ->bulkActions([
                    BulkAction::make('approve_selected')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records){
                            try {
                                foreach($records as $record){
                                    $record->status = 'approved';
                                    $record->approved_by = $this->user->employee->id;
                                    $record->approved_at = Carbon::now()->format('Y-m-d H:i:s');
                                    $record->approver_nik = $this->user->employee->nik;
                                    $record->approver_name = $this->user->employee->name;
                                    $record->approver_position = $this->user->employee->position;
                                    $record->approver_section = $this->user->employee->section->name;
                                    $record->approver_departement = $this->user->employee->section->departement->name;
                                    $record->update();
                                }
                                
                                Session::put('approve-status', 'success');
    
                                return redirect()->route('filament.admin.pages.assessment-approve', [
                                    'assessment' => $this->assessment->slug
                                ]);
                            } catch (\Throwable $th) {
                                Notification::make()->title('Approve gagal. Silahkan kontak IT.')->danger()->send();
                            }
                        })
                ])
                ->contentGrid([
                    'md' => 3,
                ])
                ->selectable();
    }

    public function approveConfirmation($id){
        $this->get_id_employee_assessed_for_approve = $id;
        $this->showModalApprove = true;
    }

    public function closeModalApprove(){
        $this->showModalApprove = false;
    }

    public function approve(){
        try {
            EmployeeAssessed::where('id', $this->get_id_employee_assessed_for_approve)->update([
                'status' => 'approved',
                'approved_by' => $this->user->employee->id,
                'approved_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'approver_nik' => $this->user->employee->nik,
                'approver_name' => $this->user->employee->name,
                'approver_position' => $this->user->employee->position,
                'approver_section' => $this->user->employee->section->name,
                'approver_departement' => $this->user->employee->section->departement->name,
            ]);

            Session::put('approve-status', 'success');

            return redirect()->route('filament.admin.pages.assessment-approve', [
                'assessment' => $this->assessment->slug
            ]);
        } catch (\Throwable $th) {
            Notification::make()->title('Approve gagal. Silahkan kontak IT.')->danger()->send();
            $this->get_id_employee_assessed_for_approve = "";
        }
    }

    public function back(){
        return redirect()->route('filament.admin.pages.assessment', ['assessment' => $this->assessment->slug]);
    }
}
