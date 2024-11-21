<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nik'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function password_not_hash(): HasOne {
        return $this->hasOne(PasswordNotHash::class);
    }

    public function employee(): HasOne {
        return $this->hasOne(Employee::class, 'nik', 'nik');
    }


    //Masih belum diimplementasikan dimana2
    public function getAssessorFor(){
        $assessor = Assessor::whereIn('assessor', [$this->nik])->get()->pluck('section.name')->toArray();
        return $assessor;
    }

    public function getApproverFor(){
        $approver = Assessor::whereIn('approver', [$this->nik])->get()->pluck('section.name')->toArray();
        return $approver;
    }
    //Masih belum diimplementasikan dimana2
}
