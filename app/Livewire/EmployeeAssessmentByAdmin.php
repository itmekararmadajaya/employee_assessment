<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessedResponse;
use App\Models\EmployeeAssessedResponseText;
use App\Models\QuestionLevel;
use App\Models\ScoreDescription;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmployeeAssessmentByAdmin extends Component implements HasForms
{
    use InteractsWithForms;

    public $user, $employee_assessed, $assessor_data;
    
    public $all_question, $question;

    public $option_selected;

    public $total_question;
    public $total_question_answered = 0;

    public $showModal = false;
    public $toast_error = false;
    public $toast_message;

    public $assessor_nik, $approver_nik, $selected_status, $assessment_date, $approved_at, $source;

    /**
     * Form Review
     */
    public $job_description = "", $assessor_comments = "", $approver_comments = "";

    public function mount($employee_assessed){
        $user = Auth::user();

        abort_if(!$user || !$user->hasRole(['admin', 'superadmin']), 403, 'Not Authorized');

        $this->user = User::with('employee')->where('id', $user->id)->first();

        $this->employee_assessed = EmployeeAssessed::where('id', Crypt::decrypt($employee_assessed))->first();
        abort_if(!$this->employee_assessed, 403, 'Employee Not Found');

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

        $this->assessor_nik = $this->employee_assessed->assessor_nik;
        $this->approver_nik = $this->employee_assessed->approver_nik;
        $this->job_description = $this->employee_assessed->job_description;
        $this->assessor_comments = $this->employee_assessed->assessor_comments;
        $this->approver_comments = $this->employee_assessed->approver_comments;
        $this->selected_status = $this->employee_assessed->status;
        $this->assessment_date = $this->employee_assessed->assessment_date;
        $this->approved_at = $this->employee_assessed->approved_at;
        $this->source = $this->employee_assessed->source;
    }

    public function buttonPrevious($question_id)
    {
        $this->storeAnswer();

        $previousQuestion = $this->all_question
            ->where('id', '<', $this->question->id)
            ->sortByDesc('id')
            ->first();

        if ($previousQuestion) {
            return redirect()->route('employee-assessment-by-admin', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $previousQuestion->id]);
        }else{
            return redirect()->route('employee-assessment-by-admin', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $this->question->id]);
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
            return redirect()->route('employee-assessment-by-admin', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $nextQuestion->id]);
        }else {
            return redirect()->route('employee-assessment-by-admin', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $this->question->id]);
        }
    }

    public function updateQuestion($question_id)
    {
        $this->storeAnswer();

        $updateQuestion = $this->all_question
            ->where('id', $question_id)
            ->first();

        if ($updateQuestion) {
            return redirect()->route('employee-assessment-by-admin', ['employee_assessed' => $this->employee_assessed->getIdEncrypted(), 'question' => $updateQuestion->id]);
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

    protected function getForms(): array
    {
        return [
            'formAssessmentDate',
            'formApprovedAt',
        ];
    }

    public function formAssessmentDate(Form $form): Form
    {
        return $form->schema([
            DateTimePicker::make('assessment_date')->required()->label('Tanggal Penilaian')
        ]);
    }

    public function formApprovedAt(Form $form): Form
    {
        return $form->schema([
            DateTimePicker::make('approved_at')->label('Tanggal Penyetujuan')
        ]);
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
             * 4. assessor_nik = NIK yang akan dijadikan isi assessor
             * 5. approver_nik = NIK yang akan dijadikan isi approver
             */
            $this->validate([
                'job_description' => 'nullable|string',
                'assessor_comments' => 'nullable|string',
                'approver_comments' => 'nullable|string',
                'assessor_nik' => 'required|exists:employees,nik',
                'approver_nik' => 'nullable|exists:employees,nik',
                'assessment_date' => 'required',
                'approved_at' => 'nullable',
                'source' => 'required|in:web,google_form'
            ], [
                // 'job_description.required' => 'Deskripsi pekerjaan harus diisi',
                // 'assessor_comments.required' => 'Komentar penilai harus diisi',
                // 'approver_comments.required' => 'Komentar penyetuju harus diisi',
                'assessor_nik.required' => 'NIK penilai harus diisi',
                'assessor_nik.exists' => 'NIK penilai harus ada di master data employee',
                'approver_nik.exists' => 'NIK penyetuju harus ada di master data employee',
                'assessment_date.required' => 'Tanggal penilaian harus dipilih',
                'source.required' => 'Sumber penilaian harus dipilih',
            ]);

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

                $this->employee_assessed->assessment_date = $this->assessment_date;

                /**
                 * store employee assessed data
                 */
                $this->employee_assessed->employee_nik = $this->employee_assessed->employee->nik;
                $this->employee_assessed->employee_name = $this->employee_assessed->employee->name;
                $this->employee_assessed->employee_position = $this->employee_assessed->employee->position;
                $this->employee_assessed->employee_section = $this->employee_assessed->employee->section->name;
                $this->employee_assessed->employee_departement = $this->employee_assessed->employee->section->departement->name;

                /**
                 * Store Assessor
                 */
                $get_assessor = Employee::where('nik', $this->assessor_nik)->first();
                $this->employee_assessed->assessor_id = $get_assessor->id;
                $this->employee_assessed->assessor_nik = $get_assessor->nik;
                $this->employee_assessed->assessor_name = $get_assessor->name;
                $this->employee_assessed->assessor_position = $get_assessor->position;
                $this->employee_assessed->assessor_section = $get_assessor->section->name;
                $this->employee_assessed->assessor_departement = $get_assessor->section->departement->name;
                
                /**
                 * Store Approver
                 */
                $get_approver = Employee::where('nik', $this->approver_nik)->first();
                if(!empty($get_approver)){
                    $this->employee_assessed->approved_by = $get_approver->id;

                    $this->employee_assessed->approved_at = $this->approved_at;
                    $this->employee_assessed->approver_nik = $get_approver->nik;
                    $this->employee_assessed->approver_name = $get_approver->name;
                    $this->employee_assessed->approver_position = $get_approver->position;
                    $this->employee_assessed->approver_section = $get_approver->section->name;
                    $this->employee_assessed->approver_departement = $get_approver->section->departement->name;
                }

                $this->employee_assessed->status = 'approved';

                /**
                 * Store aditional data
                 */
                $this->employee_assessed->job_description = $this->job_description;
                $this->employee_assessed->assessor_comments = $this->assessor_comments;
                $this->employee_assessed->approver_comments = $this->approver_comments;
                $this->employee_assessed->source = $this->source;

                /**
                 * Gice criteria and score description
                 */
                $get_score_detail = ScoreDescription::where('min', '<=', $this->employee_assessed->score)->where('max', '>=', $this->employee_assessed->score)->first();
                if($get_score_detail){
                    $this->employee_assessed->criteria = $get_score_detail->criteria;
                    $this->employee_assessed->description = $get_score_detail->description;
                }
                $this->employee_assessed->save();

                return redirect()->route('filament.admin.pages.employee-assessment-result-detail', ['employee-assessed' => $this->employee_assessed->getIdEncrypted()]);
            } catch (\Exception $e) {
                Log::error('Error calculating score: ' . $e->getMessage());
                dd($e);
                $this->showModal = false;

                $this->toast_error = true;
                $this->toast_message = 'Terjadi kesalahan saat perhitungan score';
            }
        }
    }

    public function render()
    {
        return view('livewire.employee-assessment-by-admin');
    }
}
