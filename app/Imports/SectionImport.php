<?php

namespace App\Imports;

use App\Models\Departement;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SectionImport implements ToModel, WithHeadingRow, WithValidation
{
    public $successfulRows = [];
    public $successfulQuery = [];
    public $failedRows = [];
    public $rowNumber = 1;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->rowNumber++;
        $errorMessages = [];

        if (!$this->isValidDepartement($row)) {
            $errorMessages[] = "Invalid departement: " . $row['departement'] . " or division: " . $row['division'];
        }

        if (empty($errorMessages)) {
            $this->successfulRows[] = $row;

            $departement = Departement::
                leftJoin('divisions', 'divisions.id', '=', 'departements.division_id')
                ->where('divisions.name', $row['division'])
                ->where('departements.name', $row['departement'])
                ->select('departements.*', 'divisions.name')
                ->first()->id;
            $query = Section::updateOrCreate([
                'name' => $row['name'],
                'departement_id' => $departement
            ]);

            $this->successfulQuery[] = $query->toArray();

            return $query;
        } else {
            $this->failedRows[] = [
                'row' => $this->rowNumber,
                'data' => $row,
                'errors' => $errorMessages
            ];
        }
    }

    public function isValidDepartement($row)
    {
        $departement = Departement::leftJoin('divisions', 'divisions.id', '=', 'departements.division_id')->where('divisions.name', $row['division'])->where('departements.name', $row['departement'])->select('departements.*', 'divisions.name')->get();

        return !empty($departement);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'departement' => 'required|exists:departements,name',
            'division' => 'required|exists:divisions,name',
        ];
    }
}
