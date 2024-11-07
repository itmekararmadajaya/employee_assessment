<?php

namespace App\Livewire;

use App\Models\Assessor;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessedResponse;
use App\Models\EmployeeAssessedResponseText;
use App\Models\QuestionLevel;
use App\Models\ScoreDescription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmployeeAssessment extends Component
{
    public $user, $employee_assessed, $assessor_data;

    /**
     * Status untuk membedakan apakah approver yang menilai ulang / tidak
     * Jika iya status adalah approver_reassess
     */
    public $status = "";

    public $all_question, $question;

    public $option_selected;

    public $total_question;
    public $total_question_answered = 0;

    public $showModal = false;
    public $toast_error = false;
    public $toast_message;

    /**
     * Form Review
     */
    public $job_description = "", $assessor_comments = "", $approver_comments = "";

    public function mount($employee_assessed)
    {
        $user = Auth::user();

        abort_if(!$user || !$user->hasRole('assessor'), 403, 'Not Authorized');

        $this->user = User::with('employee')->where('id', $user->id)->first();

        $this->employee_assessed = EmployeeAssessed::where('id', Crypt::decrypt($employee_assessed))->first();
        abort_if(!$this->employee_assessed, 403, 'Employee Not Found');

        $this->status = request('status', "");
        /**
         * Cek apakah assessor is approver
         */
        $this->assessor_data = Assessor::where('section_id', $this->employee_assessed->employee->section->id)->whereIn('assessor', [$this->employee_assessed->assessor_nik])->first();
        
        $this->employee_assessed->employee_nik = $this->employee_assessed->employee->nik;
        $this->employee_assessed->employee_name = $this->employee_assessed->employee->name;
        $this->employee_assessed->employee_position = $this->employee_assessed->employee->position;
        $this->employee_assessed->employee_section = $this->employee_assessed->employee->section->name;
        $this->employee_assessed->employee_departement = $this->employee_assessed->employee->section->departement->name;
        if($this->status != "approver_reassess"){
            $this->employee_assessed->assessor_id = $this->user->employee->id;
            $this->employee_assessed->assessor_nik = $this->user->employee->nik;
            $this->employee_assessed->assessor_name = $this->user->employee->name;
            $this->employee_assessed->assessor_position = $this->user->employee->position;
            $this->employee_assessed->assessor_section = $this->user->employee->section->name;
            $this->employee_assessed->assessor_departement = $this->user->employee->section->departement->name;
        }
        $this->employee_assessed->save();
        
        $level = QuestionLevel::where('name', $this->employee_assessed->employee->position)->first();
        abort_if(!$level, 403, 'Question Level Not Found');
        
        if ($level === null || $level->questions === null || $level->questions->isEmpty()) {
            abort(403, 'Question Not Found');
        }
        
        $this->all_question = $level->questions->each(function ($question, $key) {
            $question->question_number = $key + 1;
            $get_response = EmployeeAssessedResponse::where('employee_assessed_id', $this->employee_assessed->id)->where('question_id', $question->id)->first();
            if ($get_response) {
                $question->selected_option = true;
            } else {
                $question->selected_option = false;
            }
            return $question;
        });
        
        $this->total_question = count($this->all_question);
        foreach($this->all_question as $question){
            if(!empty($question['selected_option'])){
                $this->total_question_answered ++;
            }
        }

        $questionId = request('question');
        $this->question = $this->all_question->firstWhere('id', $questionId) ?? $this->all_question->first();

        abort_if(!$this->question, 403, 'Question Not Found');

        $get_response = EmployeeAssessedResponse::where('employee_assessed_id', $this->employee_assessed->id)->where('question_id', $this->question->id)->first();
        if ($get_response) {
            $this->option_selected = $get_response->option;
        }

        $this->job_description = $this->employee_assessed->job_description;
        $this->assessor_comments = $this->employee_assessed->assessor_comments;
        $this->approver_comments = $this->employee_assessed->approver_comments;
    }

    public function buttonPrevious($question_id)
    {
        $this->storeAnswer();

        $previousQuestion = $this->all_question
            ->where('id', '<', $this->question->id)
            ->sortByDesc('id')
            ->first();

        if ($previousQuestion) {
            return redirect()->route('employee-assessment', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $previousQuestion->id]);
        }else{
            return redirect()->route('employee-assessment', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $this->question->id]);
        }
    }

    public function buttonNext($question_id)
    {
        $this->storeAnswer();

        $nextQuestion = $this->all_question
            ->where('id', '>', $this->question->id)
            ->sortBy('id')
            ->first();

        if ($nextQuestion) {
            return redirect()->route('employee-assessment', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $nextQuestion->id]);
        }else {
            return redirect()->route('employee-assessment', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $this->question->id]);
        }
    }

    public function updateQuestion($question_id)
    {
        $this->storeAnswer();

        $updateQuestion = $this->all_question
            ->where('id', $question_id)
            ->first();

        if ($updateQuestion) {
            return redirect()->route('employee-assessment', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $updateQuestion->id]);
        }
    }

    public function openModal()
    {
        $this->storeAnswer();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function storeAnswer()
    {
        if (!empty($this->option_selected)) {
            EmployeeAssessedResponse::updateOrCreate([
                'employee_assessed_id' => $this->employee_assessed->id,
                'question_id' => $this->question->id,
            ], [
                'option' => $this->option_selected,
                'score' => $this->option_selected * $this->question->weight,
            ]);
        }
    }

    public function finishTest()
    {
        $responses = EmployeeAssessedResponse::where('employee_assessed_id', $this->employee_assessed->id)->get();
        if (count($responses) != count($this->all_question)) {
            $this->showModal = false;

            $this->toast_error = true;
            $this->toast_message = 'Harap menjawab semua pertanyaan.';
        } else {
            /**
             * Validasi input
             * 1. approver_comments = Comment input for employee from approver
             * 2. assessor_comments = Comment input for employee from assessor
             * 3. job_description = Job description that comment by assessor
             */
            if($this->status != 'approver_reassess'){
                $this->validate([
                    'job_description' => 'required|string',
                    'assessor_comments' => 'required|string',
                ], [
                    'job_description.required' => 'Deskripsi pekerjaan harus diisi',
                    'assessor_comments.required' => 'Komentar penilai harus diisi',
                ]);
            }else {
                $this->validate([
                    'approver_comments' => 'required|string',
                ], [
                    'approver_comments.required' => 'Komentar penyetuju harus diisi',
                ]);
            }

            try {
                $total_score = 0;

                /**
                 * Store response to database
                 */
                foreach ($responses as $response) {
                    $total_score += $response->score;

                    EmployeeAssessedResponseText::updateOrCreate([
                        'employee_assessed_id' => $this->employee_assessed->id,
                        'aspect' => $response->question->aspect,
                        'question' => $response->question->question
                    ], [
                        'option' => $response->option,
                        'weight' => $response->question->weight,
                        'score' => $response->score,
                    ]);
                }

                /**
                 * Calculate score
                 */
                $this->employee_assessed->score = ($total_score / 100) * 2;
                $this->employee_assessed->assessment_date = Carbon::now()->format('Y-m-d H:i:s');

                /**
                 * store employee assessed data
                 */
                $this->employee_assessed->employee_nik = $this->employee_assessed->employee->nik;
                $this->employee_assessed->employee_name = $this->employee_assessed->employee->name;
                $this->employee_assessed->employee_position = $this->employee_assessed->employee->position;
                $this->employee_assessed->employee_section = $this->employee_assessed->employee->section->name;
                $this->employee_assessed->employee_departement = $this->employee_assessed->employee->section->departement->name;

                /**
                 * Give assessor and approver. differentiated based on status
                 * 1. status = approver_reassess => Approver menilai ulang
                 */
                if($this->status != 'approver_reassess'){
                    $this->employee_assessed->assessor_id = $this->user->employee->id;
                    $this->employee_assessed->assessor_nik = $this->user->employee->nik;
                    $this->employee_assessed->assessor_name = $this->user->employee->name;
                    $this->employee_assessed->assessor_position = $this->user->employee->position;
                    $this->employee_assessed->assessor_section = $this->user->employee->section->name;
                    $this->employee_assessed->assessor_departement = $this->user->employee->section->departement->name;
                    $this->employee_assessed->status = 'done';
                }
                else{
                    $this->employee_assessed->approved_by = $this->user->employee->id;
                    $this->employee_assessed->approved_at = Carbon::now()->format('Y-m-d H:i:s');
                    $this->employee_assessed->approver_nik = $this->user->employee->nik;
                    $this->employee_assessed->approver_name = $this->user->employee->name;
                    $this->employee_assessed->approver_position = $this->user->employee->position;
                    $this->employee_assessed->approver_section = $this->user->employee->section->name;
                    $this->employee_assessed->approver_departement = $this->user->employee->section->departement->name;
                    $this->employee_assessed->status = 'approved';
                }

                /**
                 * Untuk assessor dan approver nik sama
                 */
                // if(){

                // }

                /**
                 * Store aditional data
                 */
                if($this->status != 'approver_reassess'){
                    $this->employee_assessed->job_description = $this->job_description;
                    $this->employee_assessed->assessor_comments = $this->assessor_comments;
                }else {
                    $this->employee_assessed->approver_comments = $this->approver_comments;
                }

                /**
                 * Gice criteria and score description
                 */
                $get_score_detail = ScoreDescription::where('min', '<=', $this->employee_assessed->score)->where('max', '>=', $this->employee_assessed->score)->first();
                if($get_score_detail){
                    $this->employee_assessed->criteria = $get_score_detail->criteria;
                    $this->employee_assessed->description = $get_score_detail->description;
                }
                $this->employee_assessed->save();

                if($this->status != 'approver_reassess'){
                    return redirect()->route('filament.admin.pages.assessment-detail', [
                        'assessment' => $this->employee_assessed->employee_assessment->slug, 
                        'employee' => Crypt::encrypt($this->employee_assessed->employee_id)]);
                }else{
                    return redirect()->route('filament.admin.pages.assessment-approve-detail', [
                        'employee-assessed' => Crypt::encrypt($this->employee_assessed->id)
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error calculating score: ' . $e->getMessage());
                
                $this->showModal = false;

                $this->toast_error = true;
                $this->toast_message = 'Terjadi kesalahan saat perhitungan score';
            }
        }
    }

    public function render()
    {
        return view('livewire.employee-assessment');
    }
}
