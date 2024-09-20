<?php

namespace App\Filament\Resources\EmployeeAssessmentResource\Pages;

use App\Filament\Resources\EmployeeAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeAssessments extends ListRecords
{
    protected static string $resource = EmployeeAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
