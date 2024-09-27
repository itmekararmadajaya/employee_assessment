<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAssessedResponseText extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_assessed_id',
        'aspect',
        'question',
        'option',
        'weight',
        'score',
    ];

    public function employee_assessed(): BelongsTo {
        return $this->belongsTo(EmployeeAssessed::class, 'employee_assessed_id');
    }
}
