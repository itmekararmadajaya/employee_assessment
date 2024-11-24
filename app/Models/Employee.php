<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'position',
        'status',
        'section_id',
    ];

    public function getAssessmentStatusAttribute()
    {
        //Cara panggilnya dengan assessment_status
        return $this->assessments()->exists() ? $this->assessments->status : 'not_assessed';
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }

    public function section(): BelongsTo {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function assessments()
    {
        return $this->hasMany(EmployeeAssessed::class, 'employee_id');
    }

    public function assessedAsAssessor()
    {
        return $this->hasMany(EmployeeAssessed::class, 'assessor_id');
    }

    public function approvals()
    {
        return $this->hasMany(EmployeeAssessed::class, 'approved_by');
    }

    public function getAssessorAttribute(){
        $assessor = Assessor::where('section_id', $this->section_id)->get();
        if($assessor){
            return $assessor->pluck('assessor');
        }
    }
}
