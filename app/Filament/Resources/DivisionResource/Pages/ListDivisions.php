<?php

namespace App\Filament\Resources\DivisionResource\Pages;

use App\Filament\Resources\DivisionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListDivisions extends ListRecords
{
    protected static string $resource = DivisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_division')->color('gray')
            ->url(fn (): string => route('filament.admin.pages.division-import')),
            Actions\CreateAction::make(),
        ];
    }
}
