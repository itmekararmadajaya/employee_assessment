<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'departement_id',
    ];

    public function departement(): BelongsTo {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }

    public function employees(): HasMany {
        return $this->hasMany(Employee::class, 'section_id', 'id');
    }
}
