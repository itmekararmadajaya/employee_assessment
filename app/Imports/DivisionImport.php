<?php

namespace App\Imports;

use App\Models\Division;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DivisionImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return Division::updateOrCreate([
            'name' => $row['name']
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string'
        ];
    }
}
