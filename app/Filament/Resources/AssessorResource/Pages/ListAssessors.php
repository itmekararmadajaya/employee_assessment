<?php

namespace App\Filament\Resources\AssessorResource\Pages;

use App\Filament\Resources\AssessorResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListAssessors extends ListRecords
{
    protected static string $resource = AssessorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_assessor')->color('gray')
            ->url(fn (): string => route('filament.admin.pages.assessor-import')),
            Actions\CreateAction::make(),
        ];
    }
}
