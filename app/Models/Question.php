<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_level_id',
        'aspect',
        'question',
        'weight',
    ];

    public function getQuestionIdEncrypted(){
        return Crypt::encrypt($this->data['id']);
    }

    public function question_level(): BelongsTo {
        return $this->belongsTo(QuestionLevel::class, 'question_level_id', 'id');
    }

    public function question_options(): HasMany {
        return $this->hasMany(QuestionOption::class);
    }

    public function employee_assessed_responses(): HasMany {
        return $this->hasMany(EmployeeAssessedResponse::class);
    }
}
