<?php

namespace App\Filament\Pages;

use App\Imports\EmployeeImport as ImportsEmployeeImport;
use App\Models\Position;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeImport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.employee-import';

    public $file, $positions;

    public $error_imports, $success_imports;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount()
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Not Authorized');
        }

        $this->positions = implode(' / ', Position::get()->pluck('name')->toArray());
    }

    public function downloadTemplate()
    {
        if (Storage::disk('public')->exists('template/template_import_employee.xlsx')) {
            return Storage::disk('public')->download('template/template_import_employee.xlsx');
        } else {
            return Notification::make()
                ->title('File template not found. Please contact IT')
                ->danger()
                ->send();
        }
    }

    public function importEmployee()
    {
        $this->success_imports = '';
        $this->error_imports = '';

        $this->validate([
            'file' => 'required|file|mimes:xlsx'
        ]);

        try {
            $importer = new ImportsEmployeeImport();
            Excel::import($importer, $this->file);

            // Access successful rows
            $successfulRows = $importer->successfulRows;

            // Access failed rows
            $failedRows = $importer->failedRows;
            dd($successfulRows, $failedRows);

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
