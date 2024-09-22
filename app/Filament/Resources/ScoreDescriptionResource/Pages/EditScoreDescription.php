<?php

namespace App\Filament\Resources\ScoreDescriptionResource\Pages;

use App\Filament\Resources\ScoreDescriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScoreDescription extends EditRecord
{
    protected static string $resource = ScoreDescriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
