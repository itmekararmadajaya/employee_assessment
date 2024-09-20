<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuestionLevel;
use App\Models\QuestionOption;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class QuestionCreate extends Component
{
    public $error_toast = false;

    public $levels;

    //Form
    public $level, $aspect, $description, $weight;

    public function mount(){
        $this->levels = QuestionLevel::all();
    }

    public function create(){
        
        $this->error_toast = false;
        $this->validate([
            'level' => 'required|exists:question_levels,id',
            'aspect' => 'required|string',
            'description' => 'required|string',
            'weight' => 'required|int'
        ]);

        try {
            $question = new Question;
            $question->question_level_id = $this->level;
            $question->aspect = $this->aspect;
            $question->question = $this->description;
            $question->weight = $this->weight;
            $question->save();

            for($i=0; $i<5; $i++){
                $option = new QuestionOption;
                $option->question_id = $question->id;
                $option->option = $i+1;
                $option->content = '';
                $option->save();
            }

            Session::put('success', 'Success add new Question, let setting options');
            return redirect()->route('question-edit', $question->id);
        } catch (Exception $e) {
            dd($e);
            $this->error_toast = true;
        }
    }

    public function render()
    {
        return view('livewire.question-create');
    }
}
