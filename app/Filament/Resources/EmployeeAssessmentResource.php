<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeAssessmentResource\Pages;
use App\Filament\Resources\EmployeeAssessmentResource\RelationManagers;
use App\Models\EmployeeAssessment;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class EmployeeAssessmentResource extends Resource
{
    protected static ?string $model = EmployeeAssessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Assessment';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->unique(ignorable: fn($record) => $record)
                        ->required()
                        ->reactive()
                        ->live(debounce: 500)
                        ->afterStateUpdated(function($state, callable $set){
                            $set('slug', Str::slug($state));
                        })
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->readOnly()
                        ->maxLength(255)
                        ->helperText('Slug tergenerate otomatis'),
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull(),
                    Forms\Components\DateTimePicker::make('time_open')
                        ->required(),
                    Forms\Components\DateTimePicker::make('time_close')
                        ->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('time_open')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_close')
                    ->dateTime()
                    ->sortable(),
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
                //
            ])
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
            'index' => Pages\ListEmployeeAssessments::route('/'),
            'create' => Pages\CreateEmployeeAssessment::route('/create'),
            'edit' => Pages\EditEmployeeAssessment::route('/{record}/edit'),
        ];
    }
}
