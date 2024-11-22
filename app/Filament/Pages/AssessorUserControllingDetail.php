<?php

namespace App\Filament\Pages;

use App\Models\Assessor;
use App\Models\Employee;
use App\Models\EmployeeAssessment;
use App\Models\Position;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
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
use Illuminate\Support\Facades\DB;

class AssessorUserControllingDetail extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessor-user-controlling-detail';

    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public $assessment, $user_assessor, $assessor_list;

    public function mount(){
        $user = Auth::user();
        if (!$user && !$user->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Not Authorized');
        }

        $slug = request('assessment');
        $this->assessment = EmployeeAssessment::where('slug', $slug)->first();
        abort_unless($this->assessment, 403, 'Employee Assessment Not Found');

        $user_id = request('user_id');
        abort_if(empty($user_id), 403, 'User Not Found');
        $this->user_assessor = User::with('employee')->where('id', $user_id)->first();
        abort_if(empty($this->user_assessor), 403, 'User Not Found');
        
        $this->assessor_list = Assessor::whereIn('assessor', [$this->user_assessor->employee->nik])->get()->map(function($data){
            $data->count_of_employee = Employee::leftJoin('sections', 'employees.section_id', '=', 'sections.id')->where('section_id', $data->section_id)->whereIn('position', $data->assessed)->count();
            return $data;
        });
    }

    public function table(Table $table): Table {
        $assessment_id = $this->assessment->id;
        $assessor_nik = $this->user_assessor->nik;

        return $table
            ->query(Employee::query()
            ->leftJoin('employee_assesseds', function ($join) use ($assessment_id) {
                $join->on('employees.id', '=', 'employee_assesseds.employee_id')
                    ->where('employee_assesseds.employee_assessment_id', $assessment_id);
            })
            ->join('assessors', function($join) use($assessor_nik){
                $join->on('employees.section_id', '=','assessors.section_id')
                    ->whereIn('assessors.assessor', [$assessor_nik]);
            })
            ->whereRaw('assessors.assessed LIKE CONCAT("%", employees.position ,"%")')
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
                'assessors.assessed'
            ))
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
}
