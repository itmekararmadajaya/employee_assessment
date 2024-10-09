<?php

namespace App\Filament\Pages;

use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessedResponseText;
use App\Models\ScoreDescription;
use App\Policies\EmployeeAssessedResponsePolicy;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

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
        try {
            $pdf = Pdf::loadView('templates.employee_assessment_result_pdf', [
                'employee_assessed' => $this->employee_assessed,
                'score_detail' => $this->score_detail,
                'employee_assessed_response' => $this->employee_assessed_response,
                'employee_assessed_response_summary' => $this->employee_assessed_response_summary,
                'score_description' => $this->score_description,
            ]);

            $file_name = 'Filename' . '.pdf';
            return response()->streamDownload(fn() => print($pdf->output()), $file_name);
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error($e->getMessage());
            return Notification::make()->warning()->title('Something was error. Please contact IT')->send();
        }
    }

    public function back(){
        return redirect()->route('filament.admin.pages.employee-assessment-result', [
            'employee-assessment' => $this->employee_assessed->employee_assessment->slug,
            'status' => $this->employee_assessed->status
        ]);
    }
}
