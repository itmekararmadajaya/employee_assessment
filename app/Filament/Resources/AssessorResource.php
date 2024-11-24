<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessorResource\Pages;
use App\Filament\Resources\AssessorResource\RelationManagers;
use App\Models\Assessor;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssessorResource extends Resource
{
    protected static ?string $model = Assessor::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    
    protected static ?string $navigationGroup = 'Assessment';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\Select::make('section_id')
                        ->relationship('section', 'name')
                        ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->name." | ".$record->departement->name." | ".$record->departement->division->name)
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('assessor')
                        ->options(User::role('assessor')->get()->pluck('nik', 'nik'))
                        ->label('Assessor / Penilai')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('assessed')
                        ->options(Position::get()->pluck('name', 'name'))
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->label('Assessed / Yang Dinilai')
                        ->helperText('Yang dinilai dapat lebih dari 1')
                        ->required()
                        ->beforeStateDehydrated(function ($state, $set) {
                            if (is_array($state)) {
                                sort($state); // Urutkan array
                                $set('assessed', $state); // Set ulang state yang sudah diurutkan
                            }
                        }),
                    Select::make('approver')
                        ->options(User::role('assessor')->get()->pluck('nik', 'nik'))
                        ->searchable()
                        ->preload()
                        ->label('Approver / Pemberi Persetujuan')
                        ->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('section.departement.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('section.departement.division.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('assessor')
                    ->searchable()
                    ->listWithLineBreaks(),
                Tables\Columns\TextColumn::make('assessed')
                    ->searchable()
                    ->listWithLineBreaks(),
                Tables\Columns\TextColumn::make('approver')
                    ->searchable()
                    ->listWithLineBreaks(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('assessor')
                    ->form([
                        TextInput::make('assessor')
                    ])
                    ->query(function(Builder $query, array $data){
                        if($data['assessor'] != null){
                            $query->whereIn('assessor', [$data['assessor']]);
                        }
                    }),
                Filter::make('assessed')
                    ->form([
                        Select::make('assessed')->options(Position::get()->pluck('name', 'name'))
                    ])
                    ->query(function(Builder $query, array $data){
                        if($data['assessed'] != null){
                            $query->where('assessed', 'LIKE', '%'. $data['assessed'] .'%');
                        }
                    }),
                Filter::make('approver')
                    ->form([
                        TextInput::make('approver')
                    ])
                    ->query(function(Builder $query, array $data){
                        if($data['approver'] != null){
                            $query->whereIn('approver', [$data['approver']]);
                        }
                    }),
                SelectFilter::make('section')->relationship('section', 'name'),
                SelectFilter::make('departement')->relationship('section.departement', 'name')
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessors::route('/'),
            'create' => Pages\CreateAssessor::route('/create'),
            'edit' => Pages\EditAssessor::route('/{record}/edit'),
        ];
    }
}
