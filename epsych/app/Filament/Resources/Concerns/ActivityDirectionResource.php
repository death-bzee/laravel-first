<?php

namespace App\Filament\Resources\Concerns;

use App\Filament\Resources\Concerns\ActivityDirectionResource\Pages;
use App\Models\Concerns\ActivityDirection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityDirectionResource extends Resource
{
    use Translatable;

    protected static ?string $model = ActivityDirection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Направление деятельности';
    protected static ?string $modelLabel = 'Направление';
    protected static ?string $navigationGroup = 'Выпадающие списки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('active')
                    ->label('Активен')
                    ->default(true),
                Forms\Components\TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('active')
                    ->label('Активен'),
                Tables\Columns\TextInputColumn::make('title')
                    ->label('Заголовок')
                    ->rules(['required', 'max:255'])
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
            'index' => Pages\ListActivityDirections::route('/'),
            'create' => Pages\CreateActivityDirection::route('/create'),
            'edit' => Pages\EditActivityDirection::route('/{record}/edit'),
        ];
    }
}
