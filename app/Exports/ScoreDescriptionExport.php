<?php

namespace App\Exports;

use App\Models\ScoreDescription;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ScoreDescriptionExport implements FromCollection, WithHeadings, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ScoreDescription::orderBy('max', 'desc')->get();
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

    public function title(): string
    {
        return 'Score Description';
    }
}
