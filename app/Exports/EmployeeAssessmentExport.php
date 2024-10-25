<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class EmployeeAssessmentExport implements FromArray, WithEvents, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        // return array_merge(array_keys($this->data[0]), ['total score', 'final score', 'kriteria']);
        return array_merge(array_keys($this->data[0]));
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $event->sheet->setCellValue("F{$row}", "=(SUM(I{$row}:$highestColumn{$row})/100)*2");

                    /**
                     * Tidak bisa karena B+ pasti terbaja B
                     */
                    // $event->sheet->setCellValue("H{$row}", "=INDEX(''Worksheet 1'!E2:E6,MATCH(Worksheet!G{$row},'Worksheet 1'!D2:D6),0)");
                }
            }
        ];
    }
}
