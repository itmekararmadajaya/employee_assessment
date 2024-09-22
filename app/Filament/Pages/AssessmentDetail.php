<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessedResponse;
use App\Models\EmployeeAssessedResponseText;
use App\Models\EmployeeAssessment;
use App\Models\ScoreDescription;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AssessmentDetail extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessment-detail';

    public $user, $employee_assessed, $employee_assessed_response, $employee_assessed_response_summary, $score_description, $score_detail;

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

        $employeeId = request('employee');
        $assessmentSlug = request('assessment');

        abort_if(!$employeeId || !$assessmentSlug, 403, 'Not Authorized');

        $employee = Employee::find(Crypt::decrypt($employeeId));
        $assessment = EmployeeAssessment::where('slug', $assessmentSlug)->first();

        abort_if(!$employee || !$assessment, 403, 'Employee or Assessment not found');

        /**
         * Generate employee assessed
         */
        $this->employee_assessed = EmployeeAssessed::firstOrCreate([
            'employee_assessment_id' => $assessment->id,
            'employee_id' => $employee->id,
        ]);
        abort_if(!$this->employee_assessed, 403, 'Employee not found');

        if ($this->employee_assessed->status == 'on_progress') {
            return redirect()->route('employee-assessment', $this->employee_assessed->getIdEncrypted());
        }

        $get_score_detail = ScoreDescription::where('min', '>=', $this->employee_assessed->score)->where('max', '<=', $this->employee_assessed->score)->first();
        $this->score_detail = [
            'criteria' => $get_score_detail ? $get_score_detail->criteria : '',
            'description' =>
            $get_score_detail ? $get_score_detail->description : '',
        ];

        $this->employee_assessed_response = EmployeeAssessedResponseText::where('employee_assessed_id', $this->employee_assessed->id)->get();
        $this->employee_assessed_response_summary = [
            'option' => $this->employee_assessed_response->sum('option'),
            'score' => $this->employee_assessed_response->sum('score'),
        ];

        $this->score_description = ScoreDescription::get();
    }
}
