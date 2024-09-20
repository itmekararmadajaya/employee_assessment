<?php

namespace App\Livewire;

use App\Models\Question as ModelsQuestion;
use App\Models\QuestionLevel;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Question extends Component
{
    public $toast_success = false, $toast_error = false, $toast_message;

    public $levels, $level_selected;
    public $questions = [];

    public function mount(){
        if(Session::has('success')){
            $this->toast_success = true;
            $this->toast_message = Session::get('success');
            Session::remove('success');
        }

        $this->levels = QuestionLevel::all();
        if(request('level')){
            $this->level_selected = QuestionLevel::find(request('level'));
        }

        if(!empty($this->level_selected)){
            $this->questions = ModelsQuestion::with('question_options')->where('question_level_id', $this->level_selected->id)->get();
        }
    }

    public function render()
    {
        return view('livewire.question');
    }
}
