<?php

use App\Http\Middleware\CustomFilamentAuth;
use App\Livewire\EmployeeAssessment;
use App\Livewire\Question;
use App\Livewire\QuestionCreate;
use App\Livewire\QuestionEdit;
use App\Livewire\ShowQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $user = Auth::user();
    if($user->hasRole(['admin', 'superadmin'])){
        $route = 'filament.admin.resources.employee-assessments.index';
    }elseif($user->hasRole(['assessor'])) {
        $route = 'filament.admin.pages.assessment';
    }
    return redirect()->route($route);
})->middleware(CustomFilamentAuth::class);

Route::get('show-question', ShowQuestion::class)->name('show-question')->middleware(CustomFilamentAuth::class);

Route::get('/question', Question::class)->name('question')->middleware(CustomFilamentAuth::class);
Route::get('/question/create', QuestionCreate::class)->name('question-create')->middleware(CustomFilamentAuth::class);
Route::get('/question/edit/{id}', QuestionEdit::class)->name('question-edit')->middleware(CustomFilamentAuth::class);

Route::get('employee-assessment/{employee_assessed}', EmployeeAssessment::class)->name('employee-assessment')->middleware(CustomFilamentAuth::class);
