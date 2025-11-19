<?php

namespace App\Filament\Resources;

use App\Enums\LevelGroupTypeEnum;
use App\Filament\Resources\LevelGroupResource\Pages;
use App\Models\LevelGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LevelGroupResource extends Resource
{
    use Translatable;

    protected static ?string $model = LevelGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 7;
    protected static ?string $pluralModelLabel = 'Группы уровней';
    protected static ?string $modelLabel = 'Группа';
    protected static ?string $navigationGroup = 'Тестирование';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                Select::make('type')
                    ->label('Тип')
                    ->options(LevelGroupTypeEnum::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Название группы')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn(LevelGroupTypeEnum $state) => $state->getLabel())
                    ->searchable(),
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

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLevelGroups::route('/'),
            'create' => Pages\CreateLevelGroup::route('/create'),
            'edit' => Pages\EditLevelGroup::route('/{record}/edit'),
        ];
    }
}
