<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Import User Assessor')->color('gray')
            ->url(fn (): string => route('filament.admin.pages.user-assessor-import')),
            Action::make('Create User')
            ->url(fn (): string => route('filament.admin.pages.user-create')),
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array {
        return [
            'assessor' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->role('assessor')),
            'admin' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->role('admin')),
            'superadmin' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->role('superadmin')),
        ];
    }
}
