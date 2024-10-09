<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessor extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'assessed',
        'assessor',
        'approver'
    ];

    protected $casts = [
        'assessed' => 'array',
    ];

    public function section(): BelongsTo {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}
