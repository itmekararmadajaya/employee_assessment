<?php

namespace App\Filament\Pages;

use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessment;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentMonitoring extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessment-monitoring';

    protected static ?string $title = '';

    public $assessment;
    public $chartCriteria = [];

    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public function mount(){
        $user = Auth::user();
        if (!$user && !$user->hasRole('admin', 'superadmin')) {
            abort(403, 'Not Authorized');
        }

        $assessment_slug = request('assessment');
        if($assessment_slug == ""){
            abort(403, 'Page Not Found');
        }

        $this->assessment = EmployeeAssessment::where('slug', $assessment_slug)->first();

        if(empty($this->assessment)){
            abort(403, 'Page Not Found');
        }

        $this->chartCriteria();
    }

    public function chartCriteria(){
        $datas = EmployeeAssessed::
        leftJoin('employee_assessments', 'employee_assesseds.employee_assessment_id', '=', 'employee_assessments.id')
        ->select('criteria', DB::raw('count(*) as total'))
        ->where('employee_assessments.slug', $this->assessment->slug)
        ->where('employee_assesseds.status', 'approved')
        ->groupBy('criteria')
        ->get()
        ->pluck('total', 'criteria')
        ->sortKeys()
        ->toArray();
        
        $this->chartCriteria = [
            'label' => array_keys($datas),
            'value' => array_values($datas),
        ];
    }

    public function back(){
        return redirect()->route("filament.admin.pages.employee-assessment-result", ["employee-assessment" => $this->assessment->slug]);
    }
}
