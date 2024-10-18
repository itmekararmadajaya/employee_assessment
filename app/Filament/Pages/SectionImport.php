<?php

namespace App\Filament\Pages;

use App\Imports\SectionImport as ImportsSectionImport;
use App\Models\Departement;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SectionImport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.section-import';

    public $file, $level;

    public $error_imports, $success_imports, $error_imports_relation;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(){
        if(!Auth::user()->hasRole(['admin', 'superadmin'])){
            abort(403, 'Not Authorized');
        }
    }

    public function downloadTemplate(){
        if(Storage::disk('public')->exists('template/template-import-section-maj.xlsx')){
            return Storage::disk('public')->download('template/template-import-section-maj.xlsx');
        }else {
            return Notification::make() 
            ->title('File template not found. Please contact IT')
            ->danger()
            ->send();
        }
    }

    public function importSection(){
        $this->success_imports = '';
        $this->error_imports = '';

        $this->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        try {
            $importer = new ImportsSectionImport();
            Excel::import($importer, $this->file);

            // Access successful rows
            $successfulRows = $importer->successfulRows;

            // Access failed rows
            $failedRows = $importer->failedRows;

            if(empty($failedRows)){
                $this->success_imports = 'Success Create All Section';
                Notification::make()->success()->title('Import Success')->send();
            }else{
                $this->error_imports_relation = $failedRows;
                Notification::make()->danger()->title('Import Failed')->send();
            }

            $this->file = "";
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
