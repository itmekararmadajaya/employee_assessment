<?php

use App\Http\Middleware\CustomFilamentAuth;
use App\Livewire\EmployeeAssessment;
use App\Livewire\EmployeeAssessmentByAdmin;
use App\Livewire\Question;
use App\Livewire\QuestionCreate;
use App\Livewire\QuestionEdit;
use App\Livewire\ShowQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

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

/**
 * Dicek di assessment_detail terlebih dahulu sudah ada employee_assessed atau belum
 * Jika belum maka akan buat employee_assessed baru lalu redirect ke route ini
 */
Route::get('employee-assessment/{employee_assessed}', EmployeeAssessment::class)->name('employee-assessment')->middleware(CustomFilamentAuth::class);
Route::get('employee-assessment-by-admin/{employee_assessed}', EmployeeAssessmentByAdmin::class)->name('employee-assessment-by-admin')->middleware(CustomFilamentAuth::class);

/**
 * Update endpoint livewire
 * 1. Hanya bisa di php artisan route:clear
 * 2. Jika php artisan route:cache maka akan error karena livewire.update duplikat
 */
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/employee_assessment/livewire/update', $handle);
});
Livewire::setScriptRoute(function ($handle) {
    return Route::get('/employee_assessment/livewire/livewire.js', $handle);
});
