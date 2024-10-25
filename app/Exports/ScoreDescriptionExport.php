<?php

namespace App\Exports;

use App\Models\ScoreDescription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScoreDescriptionExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ScoreDescription::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'min',
            'max',
            'criteria',
            'description',
        ];
    }
}
