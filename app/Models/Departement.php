<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'division_id'
    ];

    public function division(): BelongsTo {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function sections(): HasMany {
        return $this->hasMany(Section::class, 'section_id', 'id');
    }
}
