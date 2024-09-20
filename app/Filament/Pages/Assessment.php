<?php

namespace App\Filament\Pages;

use App\Models\Assessor;
use App\Models\Employee;
use App\Models\EmployeeAssessed;
use App\Models\EmployeeAssessment;
use App\Models\Section;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Assessment extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.assessment';

    public $user, $user_assessor_data, $assessment, $section, $employee_assessed;

    //Table
    public $section_id, $position;

    public static function shouldRegisterNavigation(): bool {
        return false;
    }

    public function mount(){
        $user = Auth::user();
        if(!$user && !$user->hasRole('assessor')){
            abort(403, 'Not Authorized');
        }

        $this->user = $user;

        $slug = request('assessment');
        $this->assessment = EmployeeAssessment::where('slug', $slug)->first();
        abort_unless($this->assessment, 403, 'Assessment Not Found');


        /**
         * Get user assessor data
         */
        $this->user_assessor_data = Assessor::whereIn('assessor', [$user->nik])->get();
        $this->section_id = $this->user_assessor_data->pluck('section_id')->toArray();
        $this->position = array_unique(array_merge(...$this->user_assessor_data->pluck('assessed')->toArray()));

        /**
         * Get employee assessed
         */
        // $get_employee_assessed = Employee::whereIn('section_id', $this->section_id)->whereIn('position', $this->position)->get();
    }

    public function table(Table $table){
        return $table
                ->query(Employee::query()->whereIn('section_id', $this->section_id)->whereIn('position', $this->position))
                ->columns([
                    TextColumn::make('nik')->searchable(),
                    TextColumn::make('name')->searchable(),
                    TextColumn::make('position')->searchable(),
                    TextColumn::make('section.name')->searchable(),
                ])
                ->filters([
                    // ...
                ])
                ->actions([
                    Action::make('detail')
                    ->url(fn (Employee $record): string => route('filament.admin.pages.assessment-detail', ['assessment' => $this->assessment->slug, 'employee' => Crypt::encrypt($record->id)]))
                    ->openUrlInNewTab()
                ])
                ->bulkActions([
                    // ...
                ]);
    }
}
