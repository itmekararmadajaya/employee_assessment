<?php

namespace App\Filament\Pages;

use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessedResponseText;
use App\Models\ScoreDescription;
use App\Policies\EmployeeAssessedResponsePolicy;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class EmployeeAssessmentResultDetail extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.employee-assessment-result-detail';

    public $user, $employee_assessed, $employee_assessed_response, $employee_assessed_response_summary, $score_description, $score_detail;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(){
        $user = Auth::user();
        if (!$user && !$user->hasRole('admin', 'superadmin')) {
            abort(403, 'Not Authorized');
        }

        $this->user = $user;

        $employeeAsessedId = request('employee-assessed');

        abort_if(!$employeeAsessedId, 403, 'Not Authorized');

        $employeeAsessedIdDecrypt = Crypt::decrypt($employeeAsessedId);

        abort_if(!$employeeAsessedIdDecrypt, 403, 'Employee Assessment not found');

        /**
         * Generate employee assessed
         */
        $this->employee_assessed = EmployeeAssessed::where('id', $employeeAsessedIdDecrypt)->first();
        
        abort_if(!$this->employee_assessed, 403, 'Employee not found');

        if ($this->employee_assessed->status == 'not_assessed' || $this->employee_assessed->status == 'on_progress') {
            abort(403, 'Employee not assessed');
        }

        $get_score_detail = ScoreDescription::where('min', '<=', $this->employee_assessed->score)->where('max', '>=', $this->employee_assessed->score)->first();
        $this->score_detail = [
            'criteria' => $get_score_detail ? $get_score_detail->criteria : '',
            'description' =>
            $get_score_detail ? $get_score_detail->description : '',
        ];

        $this->employee_assessed_response = EmployeeAssessedResponseText::where('employee_assessed_id', $this->employee_assessed->id)->get();
        $this->employee_assessed_response_summary = [
            'option' => $this->employee_assessed_response->sum('option'),
            'weight' => $this->employee_assessed_response->sum('weight'),
            'score' => $this->employee_assessed_response->sum('score'),
        ];

        $this->score_description = ScoreDescription::get();
    }

    public function download(){
        dd("Coming Soon");
    }

    public function back(){
        return redirect()->route('filament.admin.pages.employee-assessment-result', [
            'employee-assessment' => $this->employee_assessed->employee_assessment->slug,
            'status' => $this->employee_assessed->status
        ]);
    }
}
