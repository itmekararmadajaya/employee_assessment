<?php

namespace App\Imports;

use App\Models\Departement;
use App\Models\Division;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DepartementImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $division = Division::where('name', $row['division'])->first();
        return Departement::updateOrCreate([
            'name' => $row['name'],
            'division_id' => $division->id
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'division' => 'required|exists:divisions,name'
        ];
    }
}
