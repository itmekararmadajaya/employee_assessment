<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessorResource\Pages;
use App\Filament\Resources\AssessorResource\RelationManagers;
use App\Models\Assessor;
use App\Models\Employee;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssessorResource extends Resource
{
    protected static ?string $model = Assessor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Assessment';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\Select::make('section_id')
                        ->relationship('section', 'name')
                        ->required(),
                    Select::make('assessor')->options(User::role('assessor')->get()->pluck('nik', 'nik'))
                        ->label('Assessor / Penilai'),
                    Select::make('assessed')->options([
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
                    ])->multiple()->label('Assessed / Yang Dinilai')->helperText('Yang dinilai dapat lebih dari 1'),
                ])->columns(4)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assessor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assessed')
                    ->searchable()->listWithLineBreaks(),
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
            'index' => Pages\ListAssessors::route('/'),
            'create' => Pages\CreateAssessor::route('/create'),
            'edit' => Pages\EditAssessor::route('/{record}/edit'),
        ];
    }
}
