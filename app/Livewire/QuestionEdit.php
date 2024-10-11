<?php

namespace App\Livewire;

use App\Models\Question;
use App\Models\QuestionLevel;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class QuestionEdit extends Component
{
    public $toast_success = false, $toast_error = false, $toast_message, $showModal = false;

    public $levels, $options;
    public $question;

    //form question
    public $level, $aspect, $description, $weight;

    //form option
    public $opt_option, $opt_content;

    public function mount($id){
        if(Session::has('success')){
            $this->toast_success = true;
            $this->toast_message = Session::get('success');
            Session::remove('success');
        }

        $this->levels = QuestionLevel::all();
        
        $this->question = Question::where('id', $id)->first();
        if(empty($this->question)){
            abort(403, 'Quest not found');
        }

        $this->level = $this->question->question_level_id;
        $this->aspect = $this->question->aspect;
        $this->description = $this->question->question;
        $this->weight = $this->question->weight;

        $this->options = QuestionOption::where('question_id', $this->question->id)->get();
    }

    public function updateDataOption($id){
        $get_option = QuestionOption::findOrFail($id);
        $this->opt_option = $get_option->option;
        $this->opt_content = $get_option->content;
    }

    public function update(){
        $this->validate([
            'level' => 'required|exists:question_levels,id',
            'aspect' => 'required|string',
            'description' => 'required|string',
            'weight' => 'required|int'
        ]);

        $question = Question::where('id', $this->question->id)->first();
        $question->question_level_id = $this->level;
        $question->aspect = $this->aspect;
        $question->question = $this->description;
        $question->weight = $this->weight;
        $question->update();

        Session::put('success', 'Success add new Question, let setting options');

        return redirect()->route('question-edit', $this->question->id);
    }

    public function updateOption(){
        $this->validate([
            'opt_option' => 'required',
            'opt_content' => 'required|string'
        ]);

        $option = QuestionOption::where('question_id', $this->question->id)->where('option', $this->opt_option)->first();
        $option->content = $this->opt_content;
        $option->update();

        Session::put('success', 'Success edit option');

        return redirect()->route('question-edit', $this->question->id);
    }

    public function funcShowModal(){
        $this->showModal = true;
    }

    public function funcCloseModal(){
        $this->showModal = false;
    }

    public function deleteQuestion(){
        $this->question->delete();
        Session::put('success', 'Success delete question');

        return redirect()->route('question', ['level' => $this->question->question_level_id]);
    }

    public function render()
    {
        return view('livewire.question-edit');
    }
}
