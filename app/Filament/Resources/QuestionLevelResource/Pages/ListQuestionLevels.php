<?php

namespace App\Filament\Resources\QuestionLevelResource\Pages;

use App\Filament\Resources\QuestionLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuestionLevels extends ListRecords
{
    protected static string $resource = QuestionLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
