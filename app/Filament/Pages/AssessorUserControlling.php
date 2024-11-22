<?php

namespace App\Filament\Pages;

use App\Models\Assessor;
use App\Models\EmployeeAssessment;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AssessorUserControlling extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessor-user-controlling';

    public $assessment;
    public $assessors;
    
    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public function mount(){
        $user = Auth::user();
        if (!$user && !$user->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Not Authorized');
        }

        $slug = request('assessment');
        $this->assessment = EmployeeAssessment::where('slug', $slug)->first();
        abort_unless($this->assessment, 403, 'Employee Assessment Not Found');
    }

    public function table(Table $table): Table{
        return $table
            ->query(User::role('assessor'))
            ->columns([
                TextColumn::make('name')
            ])
            ->actions([
                Action::make('detail')->url(fn(User $record): string =>
                    route('filament.admin.pages.assessor-user-controlling-detail', [
                        'assessment' => $this->assessment->slug,
                        'user_id' => $record->id
                    ])
                )
            ]);
    }
}
