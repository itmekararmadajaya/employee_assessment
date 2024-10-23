<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessedResponseText;
use App\Models\EmployeeAssessment;
use App\Models\ScoreDescription;
use Carbon\Carbon;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class AssessmentApproveDetail extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessment-approve-detail';

    public $user, $employee_assessed, $employee_assessed_response, $employee_assessed_response_summary, $score_description;

    public $showModalApprove = false, $approve_slug, $modalTitle, $modalBody, $showModalReassess = false;

    //Form Reject
    public $rejected_msg;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(){
        $user = Auth::user();
        if (!$user && !$user->hasRole('assessor')) {
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

        $this->employee_assessed_response = EmployeeAssessedResponseText::where('employee_assessed_id', $this->employee_assessed->id)->get();
        $this->employee_assessed_response_summary = [
            'option' => $this->employee_assessed_response->sum('option'),
            'weight' => $this->employee_assessed_response->sum('weight'),
            'score' => $this->employee_assessed_response->sum('score'),
        ];

        $this->score_description = ScoreDescription::get();

        /**
         * Get notification after approval
         */
        if(Session::get('approve')){
            Notification::make()->title(Session::get('approve'))->success()->send();
            Session::remove('approve');
        }
    }

    public function form(Form $form): Form{
        return $form->schema([
            Textarea::make('rejected_msg')->label('Alasan')->placeholder('Silahkan tuliskan alasan mengapa penilaian ini ditolak')->required()
        ]);
    }

    public function openModalApprove($slug){
        $this->approve_slug = $slug;
        $this->modalTitle = $slug . ' Penilaian';
        $this->modalBody = "Anda akan $slug penilaian karyawan. Apakah Anda yakin?";
        $this->showModalApprove = true;
    }

    public function closeModalApprove(){
        $this->showModalApprove = false;
    }

    public function accept(){
        if($this->approve_slug == 'Approve'){
            $this->employee_assessed->status = 'approved';
        }elseif($this->approve_slug == 'Reject'){
            $this->validate([
                'rejected_msg' => 'required|string'
            ], [
                'rejected_msg.required' => 'Alasan harus diisi'
            ]);
            $this->employee_assessed->status = 'rejected';
            $this->employee_assessed->rejected_msg = $this->rejected_msg;
        }else{
            $this->employee_assessed->status = 'done';
        }

        $this->employee_assessed->approved_by = $this->user->employee->id;
        $this->employee_assessed->approved_at = Carbon::now()->format('Y-m-d H:i:s');
        $this->employee_assessed->approver_nik = $this->user->employee->nik;
        $this->employee_assessed->approver_name = $this->user->employee->name;
        $this->employee_assessed->approver_position = $this->user->employee->position;
        $this->employee_assessed->approver_section = $this->user->employee->section->name;
        $this->employee_assessed->approver_departement = $this->user->employee->section->departement->name;
        
        $this->employee_assessed->save();

        $session_msg = "Penilaian berhasil di ".$this->approve_slug;
        Session::put('approve', $session_msg);

        return redirect()->route('filament.admin.pages.assessment-approve-detail', [
            'employee-assessed'=> $this->employee_assessed->getIdEncrypted()
        ]);
    }

    public function openModalReassess(){
        $this->showModalReassess = true;
    }

    public function closeModalReassess(){
        $this->showModalReassess = false;
    }

    public function reassess(){
        $this->employee_assessed->status = 'on_progress';
        $this->employee_assessed->save();

        return redirect()->route('employee-assessment', [
            'employee_assessed' => $this->employee_assessed->getIdEncrypted(),
            'status' => 'approver_reassess'
        ]);
    }

    public function back(){
        return redirect()->route('filament.admin.pages.assessment-approve', ['assessment' => $this->employee_assessed->employee_assessment->slug]);
    }
}
