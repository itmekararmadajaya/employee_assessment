<?php

namespace App\Imports;

use App\Models\Departement;
use App\Models\Division;
use App\Models\Section;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeImport implements ToModel, WithHeadingRow, WithValidation
{
    public $successfulRows = [];
    public $failedRows = [];

    public function model(array $row)
    {
        if($this->isValidRow($row)){
            $this->successfulRows[] = $row;
        }else{
            $this->failedRows[] = $row;
        }
    }

    public function isValidRow($row)
    {
        /**
         * Sampai ini
         */
        $section = Section::where('name', $row['section'])
        ->first();
        return !empty($section);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|string',
            'name' => 'required|string',
            'status' => 'required|string|in:TETAP,KONTRAK',
            'position' => 'required|string|exists:positions,name',
            'section' => 'required|string|exists:sections,name',
            'departement' => 'required|string|exists:departements,name',
            'division' => 'required|string|exists:divisions,name',
        ];
    }
}
