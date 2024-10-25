<?php

namespace App\Filament\Pages;

use App\Exports\EmployeeAssessmentExport;
use App\Exports\multiSheetExport;
use App\Exports\ReportEmployeeAssessmentExport;
use App\Models\Departement;
use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessedResponseText;
use App\Models\EmployeeAssessment;
use App\Models\Position;
use App\Models\Section;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeAssessmentResult extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.employee-assessment-result';

    public $assessment, $assessment_data;

    //Url Params
    public $status;

    public $page = 'Admin';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount()
    {
        $user = Auth::user();
        if (!$user && !$user->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Not Authorized');
        }

        $slug = request('employee-assessment');
        $this->assessment = EmployeeAssessment::where('slug', $slug)->first();
        abort_unless($this->assessment, 403, 'Employee Assessment Not Found');

        /**
         * Count Assessment Data
         */
        $assessment_id = $this->assessment->id;
        $get_assessment_data = Employee::with(['assessments' => function ($query) use ($assessment_id) {
            return $query->where('employee_assessment_id', $assessment_id);
        }])->get();

        $count_blank = $get_assessment_data->filter(function ($employee) {
            return $employee->assessments->isEmpty();
        })->count();
        $count_not_assessed = $get_assessment_data->filter(function ($employee) {
            return $employee->assessments->contains('status', 'not_assessed');
        })->count();
        $count_on_progress = $get_assessment_data->filter(function ($employee) {
            return $employee->assessments->contains('status', 'on_progress');
        })->count();
        $count_done = $get_assessment_data->filter(function ($employee) {
            return $employee->assessments->contains('status', 'done');
        })->count();
        $count_approved = $get_assessment_data->filter(function ($employee) {
            return $employee->assessments->contains('status', 'approved');
        })->count();
        $count_rejected = $get_assessment_data->filter(function ($employee) {
            return $employee->assessments->contains('status', 'rejected');
        })->count();

        $this->assessment_data = [
            'not_assessed' => $count_not_assessed + $count_blank,
            'on_progress' => $count_on_progress,
            'done' => $count_done,
            'approved' => $count_approved,
            'rejected' => $count_rejected
        ];

        /**
         * Filter by status
         */
        $this->status = request('status');
    }

    public function table(Table $table): Table
    {
        $employee_assessment_id = $this->assessment->id;
        $status = $this->status;

        if ($status != null && $status != 'not_assessed') {
            $table_data = $table->query(EmployeeAssessed::query()->where('employee_assessment_id', $employee_assessment_id)->where('status', $status))
                ->recordClasses(fn(EmployeeAssessed $record) => match ($record->status) {
                    'not_assessed' => 'bg-white',
                    'on_progress' => 'bg-blue-100',
                    'done' => 'bg-yellow-100',
                    'rejected' => 'bg-red-100',
                    'approved' => 'bg-green-100',
                    default => 'bg-gray-50'
                })
                ->columns([
                    Split::make([
                        Stack::make([
                            ViewColumn::make('custom')->view('filament.table-template.employee-assessment')
                        ])
                    ])
                ])->filters([
                    Filter::make('employee_name')
                        ->form([
                            TextInput::make('name')
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query->where('employee_name', 'like', '%'.$data['name'].'%');
                        }),
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
                        ->label('Departement'),
                    SelectFilter::make('position')->options(Position::get()->pluck('name', 'name'))->multiple()
                ], layout: FiltersLayout::AboveContent)
                ->filtersTriggerAction(
                    fn(Action $action) => $action
                        ->button()
                        ->label('Filter'),
                )
                ->actions([
                    // Action::make('detail')
                    //     ->url(fn(EmployeeAssessed $record): string => route('filament.admin.pages.employee-assessment-result-detail', ['employee-assessed' => Crypt::encrypt($record->id)]))
                    //     ->visible(fn(EmployeeAssessed $record): bool => $record->status != 'on_progress')
                ], position: ActionsPosition::BeforeCells)
                ->bulkActions([
                ])
                ->contentGrid([
                    'md' => 3,
                ]);
        } else {
            $table_data = $table
                ->query(
                    Employee::query()
                        ->leftJoin('employee_assesseds', function ($join) use ($employee_assessment_id) {
                            $join->on('employees.id', '=', 'employee_assesseds.employee_id')
                                ->where('employee_assesseds.employee_assessment_id', $employee_assessment_id)
                                ->where('employee_assesseds.status', 'not_assessed');
                        })
                        ->select(
                            'employees.*',
                            DB::raw('COALESCE(employee_assesseds.employee_assessment_id, "") as employee_assessment_id'),
                            DB::raw('COALESCE(employee_assesseds.assessment_date, "") as assessment_date'),
                            DB::raw('COALESCE(employee_assesseds.employee_id, "") as employee_id'),
                            DB::raw('COALESCE(employee_assesseds.employee_nik, "") as employee_nik'),
                            DB::raw('COALESCE(employee_assesseds.employee_name, "") as employee_name'),
                            DB::raw('COALESCE(employee_assesseds.employee_position, "") as employee_position'),
                            DB::raw('COALESCE(employee_assesseds.employee_section, "") as employee_section'),
                            DB::raw('COALESCE(employee_assesseds.employee_departement, "") as employee_departement'),
                            DB::raw('COALESCE(employee_assesseds.assessor_id, "") as assessor_id'),
                            DB::raw('COALESCE(employee_assesseds.assessor_nik, "") as assessor_nik'),
                            DB::raw('COALESCE(employee_assesseds.assessor_name, "") as assessor_name'),
                            DB::raw('COALESCE(employee_assesseds.assessor_position, "") as assessor_position'),
                            DB::raw('COALESCE(employee_assesseds.assessor_section, "") as assessor_section'),
                            DB::raw('COALESCE(employee_assesseds.assessor_departement, "") as assessor_departement'),
                            DB::raw('COALESCE(employee_assesseds.status, "") as status'),
                            DB::raw('COALESCE(employee_assesseds.approved_by, "") as approved_by'),
                            DB::raw('COALESCE(employee_assesseds.approved_at, "") as approved_at'),
                            DB::raw('COALESCE(employee_assesseds.approver_nik, "") as approver_nik'),
                            DB::raw('COALESCE(employee_assesseds.approver_name, "") as approver_name'),
                            DB::raw('COALESCE(employee_assesseds.approver_position, "") as approver_position'),
                            DB::raw('COALESCE(employee_assesseds.approver_section, "") as approver_section'),
                            DB::raw('COALESCE(employee_assesseds.approver_departement, "") as approver_departement'),
                            DB::raw('COALESCE(employee_assesseds.rejected_msg, "") as rejected_msg'),
                            DB::raw('COALESCE(employee_assesseds.score, "") as score'),
                        )
                )
                ->columns([
                    Split::make([
                        Stack::make([
                            ViewColumn::make('custom')->view('filament.table-template.employee')
                        ])
                    ])
                ])
                ->filters([
                    Filter::make('employee_name')
                        ->form([
                            TextInput::make('name')
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query->where('name', 'like', '%'.$data['name'].'%');
                        }),
                    SelectFilter::make('section')
                        ->relationship('section', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload(),
                    SelectFilter::make('departement')
                        ->relationship('section.departement', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload(),
                    SelectFilter::make('position')->options(Position::get()->pluck('name', 'name'))->multiple()
                ], layout: FiltersLayout::AboveContent)
                ->filtersTriggerAction(
                    fn(Action $action) => $action
                        ->button()
                        ->label('Filter'),
                )
                ->actions([], position: ActionsPosition::BeforeCells)
                ->bulkActions([
                ])
                ->contentGrid([
                    'md' => 3,
                ]);
        }

        return $table_data;
    }

    public function back()
    {
        return redirect()->route('filament.admin.resources.employee-assessments.index');
    }

    public function exportExcel()
    {
        $employee_assessed = EmployeeAssessed::with('employee_assessed_responses_text')->where('employee_assessment_id', $this->assessment->id)->get();
        $aspects = EmployeeAssessedResponseText::whereIn('employee_assessed_id', $employee_assessed->pluck('id')->toArray())->distinct()->pluck('aspect')->toArray();
        $final_data = $employee_assessed->map(function ($assessed) use ($aspects) {
            $employee_data = [
                'nik' => $assessed->employee_nik,
                'name' => $assessed->employee_name,
                'position' => $assessed->employee_position,
                'section' => $assessed->employee_section,
                'departement' => $assessed->employee_departement,
                'score' => $assessed->score,
                'criteria' => $assessed->criteria,
                'description' => $assessed->description
            ];

            foreach ($aspects as $aspect) {
                $employee_data[$aspect] = "";
            }

            foreach ($assessed->employee_assessed_responses_text as $response) {
                $employee_data[$response->aspect] = "=" . $response->weight . "*" . $response->option;
            }
            return $employee_data;
        })->toArray();

        $file_name = 'report-'.$this->assessment->slug.'.xlsx';
        return Excel::download(new ReportEmployeeAssessmentExport($final_data), $file_name);
    }
}
