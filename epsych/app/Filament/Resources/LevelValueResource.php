<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LevelValueResource\Pages;
use App\Models\LevelValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LevelValueResource extends Resource
{
    use Translatable;

    protected static ?string $model = LevelValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 8;
    protected static ?string $pluralModelLabel = 'Значения уровней';
    protected static ?string $modelLabel = 'Значение';
    protected static ?string $navigationGroup = 'Тестирование';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('level_group_id')
                    ->label('Группа')
                    ->relationship('group', 'title')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('code')
                    ->label('Код значения для подстановки в результатах')
                    ->required(),
                TextInput::make('title')
                    ->label('Название уровня')
                    ->required(),
                TextInput::make('value')
                    ->label('Значение')
                    ->required()
                    ->numeric(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('group.title')
                    ->label('Группа')
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Код')
                    ->copyable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Название уровня')
                    ->sortable(),
                TextColumn::make('value')
                    ->label('Значение')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('level_group_id')
                    ->label('Фильтр по группе')
                    ->relationship('group', 'title') // Используем связь с LevelGroup
                    ->preload()
                    ->searchable(),
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
            'index' => Pages\ListLevelValues::route('/'),
            'create' => Pages\CreateLevelValue::route('/create'),
            'edit' => Pages\EditLevelValue::route('/{record}/edit'),
        ];
    }
}
