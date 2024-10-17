<?php

namespace App\Imports;

use App\Models\Assessor;
use App\Models\Position;
use App\Models\Section;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssessorImport implements ToModel, WithHeadingRow, WithValidation
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

        // Validate assessed
        if (!$this->isValidAssessed($row)) {
            $errorMessages[] = "Invalid position: " . $row['assessed'];
        }

        // Add row to success or failed list
        if (empty($errorMessages)) {
            $this->successfulRows[] = $row;

            $section_id = Section::leftJoin('departements', 'departements.id', '=', 'sections.departement_id')->leftJoin('divisions', 'divisions.id', '=', 'departements.division_id')->where('sections.name', $row['section'])->where('departements.name', $row['departement'])->where('divisions.name', $row['division'])->select('sections.*', 'departements.name')->first()->id;
            $assessed = explode(',', $row['assessed']);
            sort($assessed);
            $assessor = Assessor::where('section_id', $section_id)->where('assessed', json_encode($assessed))->first();
            if($assessor){
                $assessor->section_id = $section_id;
                $assessor->assessed = $assessed;
                $assessor->assessor = $row['assessor'];
                $assessor->approver = $row['approver'];
                $assessor->update();
            }else{ 
                $new_assessor = new Assessor;
                $new_assessor->section_id = $section_id;
                $new_assessor->assessed = $assessed;
                $new_assessor->assessor = $row['assessor'];
                $new_assessor->approver = $row['approver'];
                $new_assessor->save();
            }
        } else {
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

    public function isValidAssessed($row){
        $position_data = explode(',', $row['assessed']);
        $position = Position::whereIn('name', $position_data)->get()->toArray();

        return count($position_data) == count($position);
    }

    public function  rules(): array
    {
        return [
            'section' => 'required|exists:sections,name',
            'departement' => 'required|exists:departements,name',
            'assessor' => 'required|exists:employees,nik',
            'assessed' => 'required',
            'approver' => 'required|exists:employees,nik',
        ];
    }
}
