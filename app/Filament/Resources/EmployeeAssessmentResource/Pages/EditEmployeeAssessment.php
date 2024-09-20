<?php

namespace App\Filament\Resources\EmployeeAssessmentResource\Pages;

use App\Filament\Resources\EmployeeAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeAssessment extends EditRecord
{
    protected static string $resource = EmployeeAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
