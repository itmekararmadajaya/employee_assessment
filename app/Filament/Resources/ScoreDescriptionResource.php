<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScoreDescriptionResource\Pages;
use App\Filament\Resources\ScoreDescriptionResource\RelationManagers;
use App\Models\ScoreDescription;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScoreDescriptionResource extends Resource
{
    protected static ?string $model = ScoreDescription::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Assessment';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('min')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('max')
                        ->required()
                        ->numeric(),
                    Forms\Components\TextInput::make('criteria')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('description')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(6),
                ])->columns(9)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('min')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('criteria')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
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
            'index' => Pages\ListScoreDescriptions::route('/'),
            'create' => Pages\CreateScoreDescription::route('/create'),
            'edit' => Pages\EditScoreDescription::route('/{record}/edit'),
        ];
    }
}
