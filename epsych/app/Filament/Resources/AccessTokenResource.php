<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessTokenResource\Pages;
use App\Filament\Resources\AccessTokenResource\RelationManagers;
use App\Models\AccessToken;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AccessTokenResource extends Resource
{
    protected static ?string $model = AccessToken::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel = 'Токены доступа';
    protected static ?string $modelLabel = 'Токен доступа';
    protected static ?string $navigationGroup = 'Filament Shield';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('organizations')
                    ->label('Организации')
                    ->multiple()
                    ->relationship('organizations', 'bin') // Связь с организациями
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('token')
                    ->label('Токен'),

                Tables\Columns\TextColumn::make('organization_bin')
                    ->label('БИН Организации')
                    ->getStateUsing(function ($record) {
                        // Получаем все связанные организации и их БИНы
                        return $record->organizations->pluck('bin')->join(', ');
                    })
                    ->badge(),
                ViewColumn::make('links')  // Используем другой ключ
                    ->view('filament.tables.columns.access-token-links')
                    ->label('Ссылки для копирования'),

            ])
            ->filters([
                SelectFilter::make('organization_id')
                    ->label('Организация по БИН')
                    ->relationship('organizations', 'bin')
                    ->multiple() // Если хотите позволить выбирать несколько значений
                ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAccessTokens::route('/'),
            'create' => Pages\CreateAccessToken::route('/create'),
            'edit' => Pages\EditAccessToken::route('/{record}/edit'),
        ];
    }
}
