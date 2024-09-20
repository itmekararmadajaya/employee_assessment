<?php

use App\Livewire\EmployeeAssessment;
use App\Livewire\Question;
use App\Livewire\QuestionCreate;
use App\Livewire\QuestionEdit;
use App\Livewire\ShowQuestion;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});

Route::get('show-question', ShowQuestion::class)->name('show-question')->middleware('auth');

Route::get('/question', Question::class)->name('question')->middleware('auth');
Route::get('/question/create', QuestionCreate::class)->name('question-create')->middleware('auth');
Route::get('/question/edit/{id}', QuestionEdit::class)->name('question-edit')->middleware('auth');

Route::get('employee-assessment/{employee_assessed}', EmployeeAssessment::class)->name('employee-assessment')->middleware('auth');
