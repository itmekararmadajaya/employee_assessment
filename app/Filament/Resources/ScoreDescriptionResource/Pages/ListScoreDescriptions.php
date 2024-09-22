<?php

namespace App\Filament\Resources\ScoreDescriptionResource\Pages;

use App\Filament\Resources\ScoreDescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScoreDescriptions extends ListRecords
{
    protected static string $resource = ScoreDescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
