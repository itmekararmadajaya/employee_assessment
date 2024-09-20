<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\PasswordNotHash;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;


class UserAssessorImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $employee = Employee::where('nik', $row['nik'])->first();

        $password_not_hash = $row['password'] ? $row['password'] : Str::random(6);
        $password = Hash::make($password_not_hash);

        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'nik' => $row['nik'],
                'name' => $employee->name,
                'password' => $password,
            ]
        );

        PasswordNotHash::updateOrCreate(
            ['user_id' => $user->id],
            ['password' => $password_not_hash]
        );

        $user->assignRole('assessor');

        return $user;
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|string|exists:employees,nik',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
        ];
    }
}
