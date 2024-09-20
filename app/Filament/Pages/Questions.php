<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Questions extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.questions';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Questions';

    protected static ?string $navigationLabel = 'Questions';

    public static function shouldRegisterNavigation(): bool {
        return Auth::user()->hasRole(['admin', 'superadmin']);
    }

    public function mount(){
        if(!Auth::user()->hasRole(['admin', 'superadmin'])){
            abort(403, 'Not Authorized');
        }

        redirect()->route('question');
    }
}
