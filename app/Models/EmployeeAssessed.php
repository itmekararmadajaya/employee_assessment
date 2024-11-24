<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class EmployeeAssessed extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_assessment_id',
        'assessment_date',
        
        'employee_id',
        'employee_nik',
        'employee_name',
        'employee_position',
        'employee_section',
        'employee_departement',
        
        'assessor_id',
        'assessor_nik',
        'assessor_name',
        'assessor_position',
        'assessor_section',
        'assessor_departement',
        'status',
        
        'approved_by',
        'approved_at',
        'approver_nik',
        'approver_name',
        'approver_position',
        'approver_section',
        'approver_departement',

        'rejected_msg',
        
        'score',
        'criteria',
        'description',

        'job_description',
        'assessor_comments',
        'approver_comments',

        'scource', // Sumber data apakah dari web atau dari google form
    ];

    public function getIdEncrypted(){
        return Crypt::encrypt($this->id);
    }

    public function getAssessmentDate(){
        return Carbon::make($this->assessment_date)->format('d-m-Y H:i');
    }

    public function getApprovedAt(){
        return Carbon::make($this->approved_at)->format('d-m-Y H:i');
    }

    public function employee_assessment(): BelongsTo {
        return $this->belongsTo(EmployeeAssessment::class, 'employee_assessment_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function assessor()
    {
        return $this->belongsTo(Employee::class, 'assessor_id');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function employee_assessed_responses(): HasMany {
        return $this->hasMany(EmployeeAssessedResponse::class);
    }

    public function employee_assessed_responses_text(): HasMany {
        return $this->hasMany(EmployeeAssessedResponseText::class);
    }

}
