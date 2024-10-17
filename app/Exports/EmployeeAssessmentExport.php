<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
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

    public static function beforeWriting(BeforeWriting $event)
    {
        $sheet = $event->writer->getDelegate()->getActiveSheet();

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        for ($row = 2; $row <= $highestRow; $row++) {
            $sheet->setCellValue("F{$row}", "=(SUM(I{$row}:$highestColumn{$row})/100)*2");
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => [self::class, 'beforeWriting'],
        ];
    }
}
