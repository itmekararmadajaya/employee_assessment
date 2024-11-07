<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class EmployeeAssessmentResultImport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.employee-assessment-result-import';

    public static function shouldRegisterNavigation(): bool {
        return false;
    }
}
