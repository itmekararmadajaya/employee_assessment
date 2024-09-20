<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuestionLevel;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class ShowQuestion extends Component
{
    public $employee, $question, $all_question;

    public $showModal = false;
    public $toast_error = false;
    public $toast_message;

    public $total_question;
    public $total_question_answered = 0;

    public function mount(){

        $level = QuestionLevel::first();

        $this->all_question = $level->questions->each(function($question, $key){
            $question->question_number = $key+1;

            return $question;
        });
        
        $questionId = request('question');
        
        $this->question =  !empty($questionId) ? $this->all_question->firstWhere('id', Crypt::decrypt($questionId)) : $this->all_question->first();
        
        if(!$this->question){
            abort(403, 'Question Not Found');
        }
    }

    public function buttonPrevious($question_id){        
        $previousQuestion = $this->all_question
        ->where('id', '<', $this->question->id) 
        ->sortByDesc('id')                    
        ->first();                            

        if($previousQuestion){
            return redirect()->route('show-question', ['question' => Crypt::encrypt($previousQuestion->id)]);
        }
        
    }

    public function buttonNext($question_id){
        $nextQuestion = $this->all_question
        ->where('id', '>', $this->question->id)
        ->sortBy('id')
        ->first();

        if($nextQuestion){
            return redirect()->route('show-question', ['question' => Crypt::encrypt($nextQuestion->id)]);
        }
    }

    public function updateQuestion($question_id){
        $updateQuestion = $this->all_question
        ->where('id', $question_id)
        ->first();

        if($updateQuestion){
            return redirect()->route('show-question', ['question' => Crypt::encrypt($updateQuestion->id)]);
        }
    }

    public function openModal()
    {   
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.show-question');
    }
}
