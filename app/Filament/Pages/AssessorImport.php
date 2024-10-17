<?php

namespace App\Filament\Pages;

use App\Imports\AssessorImport as ImportsAssessorImport;
use App\Models\Assessor;
use App\Models\Position;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AssessorImport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessor-import';

    public $file, $positions;

    public $error_imports, $error_imports_relation, $success_imports;

    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public function mount(){
        if(!Auth::user()->hasRole(['superadmin', 'admin'])){
            abort(403, 'Not Authorized');
        }

        $this->positions = implode(' / ', Position::get()->pluck('name')->toArray());
    }

    public function downloadTemplate(){
        if(Storage::disk('public')->exists('template/template-import-assesser-maj.xlsx')){
            return Storage::disk('public')->download('template/template-import-assesser-maj.xlsx');
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
        $this->error_imports_relation = [];

        $this->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        try {
            $importer = new ImportsAssessorImport();
            Excel::import($importer, $this->file);

            // Access successful rows
            $successfulRows = $importer->successfulRows;

            // Access failed rows
            $failedRows = $importer->failedRows;
            if(empty($failedRows)){
                $this->success_imports = 'Success Create All Assessor';
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
