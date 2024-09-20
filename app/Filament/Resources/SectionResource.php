<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages;
use App\Filament\Resources\SectionResource\RelationManagers;
use App\Models\Employee;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Components\Section as ComponentsSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Employees';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ComponentsSection::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('departement_id')
                        ->relationship('departement', 'name')
                        ->required(),
                    // Select::make('assessed')->options([
                    //     'SUPPORT' => 'SUPPORT',
                    //     'LEADER' => 'LEADER',
                    //     'STAFF' => 'STAFF',
                    //     'FOREMAN' => 'FOREMAN',
                    //     'SUPERVISOR' => 'SUPERVISOR',
                    //     'ASST_MANAGER' => 'ASST.MANAGER',
                    //     'MANAGER' => 'MANAGER',
                    //     'PJS_MANAGER' => 'PJS MANAGER',
                    //     'SENIOR_MANAGER' => 'SENIOR MANAGER',
                    //     'GM' => 'GM',
                    // ])->multiple()->label('Assessed / Yang Dinilai'),
                ])->columns(4)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->label('Section'),
                Tables\Columns\TextColumn::make('departement.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departement.division.name')
                    ->numeric()
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
                SelectFilter::make('departement')->relationship('departement', 'name'),
                SelectFilter::make('division')->relationship('departement.division', 'name'),
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
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
