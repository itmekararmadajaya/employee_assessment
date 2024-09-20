<?php

namespace App\Filament\Pages;

use App\Imports\UserAssessorImport as ImportsUserAssessorImport;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserAssessorImport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.user-assessor-import';

    public $file;

    public $error_imports, $success_imports;

    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public function mount(){
        if(!Auth::user()->hasRole(['superadmin', 'admin'])){
            abort(403, 'Not Authorized');
        }
    }

    public function downloadTemplate(){
        if(Storage::disk('public')->exists('template/template-import-user-assesser-maj.xlsx')){
            return Storage::disk('public')->download('template/template-import-user-assesser-maj.xlsx');
        }else {
            Notification::make() 
            ->title('File template not found. Please contact IT')
            ->danger()
            ->send();
        }
    }

    public function importDiscQuestion(){
        $this->success_imports = '';
        $this->error_imports = '';

        $this->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        try {
            $importer = new ImportsUserAssessorImport();
            Excel::import($importer, $this->file);

            Notification::make()->success()->title('Import Success')->send();

            $this->file = "";
            $this->success_imports = 'Success Create All Question';
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
    
            $this->error_imports = [];
            
            foreach ($failures as $failure) {
                $this->error_imports[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }
        }
    }
}
