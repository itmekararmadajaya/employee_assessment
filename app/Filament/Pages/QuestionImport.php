<?php

namespace App\Filament\Pages;

use App\Imports\QuestionImport as ImportsQuestionImport;
use App\Models\QuestionLevel;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class QuestionImport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.question-import';

    public $file, $level;

    public $error_imports, $success_imports;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(){
        if(!Auth::user()->hasRole(['admin', 'superadmin'])){
            abort(403, 'Not Authorized');
        }

        $this->level = implode(', ', QuestionLevel::get()->pluck('name')->toArray());
    }

    public function downloadTemplate(){
        if(Storage::disk('public')->exists('template/template_import_question.xlsx')){
            return Storage::disk('public')->download('template/template_import_question.xlsx');
        }else {
            return Notification::make() 
            ->title('File template not found. Please contact IT')
            ->danger()
            ->send();
        }
    }

    public function importDiscNormaStandard(){
        $this->success_imports = '';
        $this->error_imports = '';

        $this->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        try {
            $importer = new ImportsQuestionImport();
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
