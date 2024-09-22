<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionLevelResource\Pages;
use App\Filament\Resources\QuestionLevelResource\RelationManagers;
use App\Models\QuestionLevel;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionLevelResource extends Resource
{
    protected static ?string $model = QuestionLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-down';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Questions';

    protected static ?string $navigationLabel = 'Levels';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('name')->options([
                        'SUPPORT' => 'SUPPORT',
                        'LEADER' => 'LEADER',
                        'STAFF' => 'STAFF',
                        'FOREMAN' => 'FOREMAN',
                        'SUPERVISOR' => 'SUPERVISOR',
                        'ASST_MANAGER' => 'ASST.MANAGER',
                        'MANAGER' => 'MANAGER',
                        'PJS_MANAGER' => 'PJS MANAGER',
                        'SENIOR_MANAGER' => 'SENIOR MANAGER',
                        'GM' => 'GM',
                    ])->searchable()->required(),
                    Forms\Components\Textarea::make('description')
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
            'index' => Pages\ListQuestionLevels::route('/'),
            'create' => Pages\CreateQuestionLevel::route('/create'),
            'edit' => Pages\EditQuestionLevel::route('/{record}/edit'),
        ];
    }
}
