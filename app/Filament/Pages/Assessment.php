<?php

namespace App\Filament\Pages;

use App\Models\Assessor;
use App\Models\Departement;
use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessment;
use App\Models\Position;
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
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Assessment extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessment';

    protected static ?string $title = "";

    public $page_title;

    public $user, $user_assessor_data, $assessment, $section, $employee_assessed, $assessment_data = [], $count_must_be_approve = 0, $user_approver_data;

    //Table
    public $section_id, $position;

    //Url Params
    public $status;

    //Page Params
    public $page = 'Assessment';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount()
    {
        $user = Auth::user();
        if (!$user && !$user->hasRole('assessor')) {
            abort(403, 'Not Authorized');
        }

        $this->user = $user;

        $slug = request('assessment');
        $this->assessment = EmployeeAssessment::where('slug', $slug)->first();
        abort_unless($this->assessment, 403, 'Assessment Not Found');

        $this->page_title = $this->assessment->name;

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
        $get_assessment_data = Employee::with(['assessments' => function ($query) use ($assessment_id) {
            return $query->where('employee_assessment_id', $assessment_id);
        }])->whereIn('section_id', $this->section_id)->whereIn('position', $this->position)->get();

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

    public function table(Table $table)
    {
        $assessment_id = $this->assessment->id;
        $status = $this->status;

        if ($status != null && $status != 'not_assessed') {
            $table_data = $table->query(EmployeeAssessed::query()->where('employee_assessment_id', $this->assessment->id)->where('assessor_id', $this->user->employee->id)->where('status', $status))                
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
                        ->label('Departement')
                ], layout: FiltersLayout::AboveContent)
                ->actions([
                ])
                ->bulkActions([
                ])
                ->contentGrid([
                    'md' => 3,
                ]);
        } else {
            $table_data = $table
                ->query(
                    Employee::query()
                        ->leftJoin('employee_assesseds', function ($join) use ($assessment_id) {
                            $join->on('employees.id', '=', 'employee_assesseds.employee_id')
                                ->where('employee_assesseds.employee_assessment_id', $assessment_id);
                        })
                        ->whereIn('section_id', $this->section_id)
                        ->whereIn('position', $this->position)
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
                            DB::raw('COALESCE(employee_assesseds.status, "") as asssessed_status'),
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
                        ->where(function ($query) {
                            $query->where('employee_assesseds.status', 'not_assessed')
                                ->orWhereNull('employee_assesseds.status');
                        })
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
                ->actions([
                ], position: ActionsPosition::BeforeCells)
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
        return redirect()->route('filament.admin.pages.list-assessment');
    }
}
