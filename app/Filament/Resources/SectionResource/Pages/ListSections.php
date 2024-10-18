<?php

namespace App\Filament\Resources\SectionResource\Pages;

use App\Filament\Resources\SectionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_section')->color('gray')
            ->url(fn (): string => route('filament.admin.pages.section-import')),
            Actions\CreateAction::make(),
        ];
    }
}
