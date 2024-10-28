<?php

namespace App\Exports;

use App\Models\ScoreDescription;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class EmployeeAssessmentExport implements FromArray, WithEvents, WithHeadings, WithTitle
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

                $countOfScoreDescription = ScoreDescription::count();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $event->sheet->setCellValue("F{$row}", "=(SUM(I{$row}:$highestColumn{$row})/100)*2");

                    $formulaCriteria = "";
                    $formulaDescription = "";

                    for ($i = 2; $i <= $countOfScoreDescription + 1; $i++) {
                        $formulaCriteria .= "IF(F{$row}>='Score Description'!B{$i},'Score Description'!D{$i},";
                        $formulaDescription .= "IF(G{$row}='Score Description'!D{$i},'Score Description'!E{$i},";
                    }
                    $formulaCriteria .= "\"NOT FOUND\"";
                    $formulaCriteria .= str_repeat(")", $countOfScoreDescription);
                    $formulaDescription .= "\"NOT FOUND\"";
                    $formulaDescription .= str_repeat(")", $countOfScoreDescription);

                    $event->sheet->setCellValue("G{$row}", "=$formulaCriteria");
                    $event->sheet->setCellValue("H{$row}", "=$formulaDescription");
                }
            }
        ];
    }

    public function title(): string
    {
        return 'Employee Assessment Report';
    }
}
