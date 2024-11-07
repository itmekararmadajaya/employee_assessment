<?php

namespace App\Filament\Pages;

use App\Models\EmployeeAssessment;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ListAssessment extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.list-assessment';
    
    protected static ?string $title = 'Periode Penilaian';

    public $employee_assessments = [];

    public static function shouldRegisterNavigation(): bool {
        return Auth::user()->hasRole('assessor');
    }
    
    public function mount(){
        if(!Auth::user()->hasRole('assessor')){
            abort(403, 'Not Authorized');
        }
        
        $this->employee_assessments = EmployeeAssessment::orderBy('created_at', 'desc')->get()->map(function($assessment) {
            $now = Carbon::now();
            if($now->lessThan($assessment->time_open)){
                $status = 'close';
            }elseif($now->greaterThan($assessment->time_close)){
                $status = 'done';
            }else {
                $status = 'open';
            }

            $assessment->status = $status;
            return $assessment;
        });
    }
}
