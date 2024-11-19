<?php

namespace App\Imports;

use App\Models\Departement;
use App\Models\Division;
use App\Models\Employee;
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
    public $rowNumber = 1;

    public function model(array $row)
    {
        $this->rowNumber++;

        // Reset error message for each row
        $errorMessages = [];

        // Validate section
        if (!$this->isValidSection($row)) {
            $errorMessages[] = "Invalid section: " . $row['section'] . " or department: " . $row['departement'] . " or division: " . $row['division'];
        }

        if (empty($errorMessages)) {
            $this->successfulRows[] = $row;

            $section_id = Section::leftJoin('departements', 'departements.id', '=', 'sections.departement_id')->leftJoin('divisions', 'divisions.id', '=', 'departements.division_id')->where('sections.name', $row['section'])->where('departements.name', $row['departement'])->where('divisions.name', $row['division'])->select('sections.*', 'departements.name')->first()->id;

            $employee = Employee::updateOrCreate([
                'nik' => $row['nik'],
            ], [
                'name' => $row['name'],
                'position' => $row['position'],
                'status' => $row['status'],
                'section_id' => $section_id,
            ]);
        }else{
            $this->failedRows[] = [
                'row' => $this->rowNumber,
                'data' => $row,
                'errors' => $errorMessages
            ];
        }
    }

    public function isValidSection($row)
    {
        $section = Section::leftJoin('departements', 'departements.id', '=', 'sections.departement_id')->leftJoin('divisions', 'divisions.id', '=', 'departements.division_id')->where('sections.name', $row['section'])->where('departements.name', $row['departement'])->where('divisions.name', $row['division'])->select('sections.*', 'departements.name')->first();

        return !empty($section);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|string',
            'name' => 'required|string',
            'status' => 'required|string|in:TETAP,KONTRAK,LEPAS',
            'position' => 'required|string|exists:positions,name',
            'section' => 'required|string|exists:sections,name',
            'departement' => 'required|string|exists:departements,name',
            'division' => 'required|string|exists:divisions,name',
        ];
    }
}
