<?php

namespace App\Filament\Resources\Concerns;

use App\Filament\Resources\Concerns\EducationLevelResource\Pages;
use App\Models\Concerns\EducationLevel;
use App\Traits\Filament\HasFilamentColumns;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class EducationLevelResource extends Resource
{
    use Translatable;
    use HasFilamentColumns;

    protected static ?string $model = EducationLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 9;
    protected static ?string $pluralModelLabel = 'Уровень образования';
    protected static ?string $modelLabel = 'Уровень образования';
    protected static ?string $navigationGroup = 'Выпадающие списки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('active')
                    ->label(__('Активен'))
                    ->required(),
                TextInput::make('title')
                    ->label(__('Заголовок'))
                    ->required()
                    ->maxLength(255),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                ToggleColumn::make('active')
                    ->label(__('Активен')),
                TextColumn::make('title')
                    ->label('Заголовок')
                    ->sortable(),

                ...self::getCreationColumns(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\LocaleSwitcher::make(),
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
            'index' => Pages\ListEducationLevels::route('/'),
            'create' => Pages\CreateEducationLevel::route('/create'),
            'edit' => Pages\EditEducationLevel::route('/{record}/edit'),
        ];
    }
}
