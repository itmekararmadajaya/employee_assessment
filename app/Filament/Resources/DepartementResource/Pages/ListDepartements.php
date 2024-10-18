<?php

namespace App\Filament\Resources\DepartementResource\Pages;

use App\Filament\Resources\DepartementResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListDepartements extends ListRecords
{
    protected static string $resource = DepartementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_departement')->color('gray')
            ->url(fn (): string => route('filament.admin.pages.departement-import')),
            Actions\CreateAction::make(),
        ];
    }
}
