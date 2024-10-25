<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserAssessorExport implements FromArray, WithHeadings
{
    public $users;

    public function __construct($users) {
        $this->users = $users;
    }
    
    public function array(): array
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'name',
            'nik',
            'email',
            'password',
        ];
    }
}
