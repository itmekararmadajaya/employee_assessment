<?php

namespace App\Livewire;

use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessedResponse;
use App\Models\EmployeeAssessedResponseText;
use App\Models\QuestionLevel;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class EmployeeAssessment extends Component
{
    public $user, $employee_assessed;

    public $all_question, $question;

    public $option_selected;

    public $showModal = false;
    public $toast_error = false;
    public $toast_message;

    public function mount($employee_assessed)
    {
        $user = Auth::user();

        abort_if(!$user || !$user->hasRole('assessor'), 403, 'Not Authorized');

        $this->user = $user;

        $this->employee_assessed = EmployeeAssessed::where('id', Crypt::decrypt($employee_assessed))->first();
        abort_if(!$this->employee_assessed, 403, 'Employee Not Found');

        $level = QuestionLevel::where('name', $this->employee_assessed->employee->position)->first();
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

        $questionId = request('question');
        $this->question = $this->all_question->firstWhere('id', $questionId) ?? $this->all_question->first();

        abort_if(!$this->question, 403, 'Question Not Found');

        $get_response = EmployeeAssessedResponse::where('employee_assessed_id', $this->employee_assessed->id)->where('question_id', $this->question->id)->first();
        if ($get_response) {
            $this->option_selected = $get_response->option;
        }
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
            try {
                $total_score = 0;
                foreach ($responses as $response) {
                    $total_score += $response->score;

                    EmployeeAssessedResponseText::updateOrCreate([
                        'employee_assessed_id' => $this->employee_assessed->id,
                        'aspect' => $response->question->aspect,
                        'question' => $response->question->question
                    ], [
                        'option' => $response->option,
                        'score' => $response->score,
                    ]);
                }

                /**
                 * Calculate score
                 */
                $this->employee_assessed->score = ($total_score / 100) * 2;
                $this->employee_assessed->assessment_date = Carbon::now()->format('Y-m-d H:i:s');

                $this->employee_assessed->employee_nik = $this->employee_assessed->employee->nik;
                $this->employee_assessed->employee_name = $this->employee_assessed->employee->name;
                $this->employee_assessed->employee_position = $this->employee_assessed->employee->position;
                $this->employee_assessed->employee_section = $this->employee_assessed->employee->section->name;
                $this->employee_assessed->employee_departement = $this->employee_assessed->employee->section->departement->name;

                $this->employee_assessed->assessor_id = $this->user->id;
                $this->employee_assessed->assessor_nik = $this->user->employee->nik;
                $this->employee_assessed->assessor_name = $this->user->employee->name;
                $this->employee_assessed->assessor_position = $this->user->employee->position;
                $this->employee_assessed->assessor_section = $this->user->employee->section->name;
                $this->employee_assessed->assessor_departement = $this->user->employee->section->departement->name;
                $this->employee_assessed->status = 'done';
                $this->employee_assessed->save();

                return redirect()->route('filament.admin.pages.assessment-detail', ['assessment' => $this->employee_assessed->employee_assessment->slug, 'employee' => Crypt::encrypt($this->employee_assessed->employee_id)]);
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
