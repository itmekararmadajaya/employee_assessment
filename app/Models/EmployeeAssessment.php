<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'time_open',
        'time_close',
    ];


    public function getFormattedDateStartTest(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['time_open'])->format('d-m-Y');
    }

    public function getFormattedTimeStartTest(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['time_open'])->format('H:i');
    }

    public function getFormattedDateCloseTest(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['time_close'])->format('d-m-Y');
    }

    public function getFormattedTimeCloseTest(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['time_close'])->format('H:i');
    }

    public function employee_assesseds(): HasMany {
        return $this->hasMany(EmployeeAssessed::class);
    }
}
