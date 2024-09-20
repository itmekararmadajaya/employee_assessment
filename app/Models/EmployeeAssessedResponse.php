<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAssessedResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_assessed_id',
        'question_id',
        'option',
        'score',
    ];

    public function employee_assessed(): BelongsTo {
        return $this->belongsTo(EmployeeAssessed::class, 'employee_assessed_id');
    }

    public function question(): BelongsTo {
        return $this->belongsTo(Question::class);
    }
}
