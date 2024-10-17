<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionLevelResource\Pages;
use App\Filament\Resources\QuestionLevelResource\RelationManagers;
use App\Models\Position;
use App\Models\QuestionLevel;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
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
                    Select::make('name')->options(Position::get()->pluck('name', 'name'))->searchable()
                    ->unique(ignoreRecord: true)
                    ->required(),
                    Forms\Components\Textarea::make('description')
                        ->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('sync_data')
                    ->label('Sync Data')
                    ->action(function () {
                        $positions = Position::get();
                        foreach($positions as $position){
                            QuestionLevel::updateOrCreate([
                                'name' => $position->name,
                            ], [
                                'description' => 'QUESTION LEVEL '.$position->name
                            ]);
                        }

                        return redirect()->route('filament.admin.resources.question-levels.index');
                    }),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
                Tables\Actions\DeleteAction::make(),
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
            // 'create' => Pages\CreateQuestionLevel::route('/create'),
            // 'edit' => Pages\EditQuestionLevel::route('/{record}/edit'),
        ];
    }
}
