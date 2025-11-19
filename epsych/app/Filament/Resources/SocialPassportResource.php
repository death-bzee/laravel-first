<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialPassportResource\Pages;
use App\Models\SocialPassport;
use App\Traits\Filament\HasFilamentColumns;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SocialPassportResource extends Resource
{
    use HasFilamentColumns;
    use Translatable;

    protected static ?string $model = SocialPassport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 8;
    protected static ?string $pluralModelLabel = 'Социальные статусы';
    protected static ?string $modelLabel = 'Социальный статус';
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
                    ->label('Активен'),
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
            'index' => Pages\ListSocialPassports::route('/'),
            'create' => Pages\CreateSocialPassport::route('/create'),
            'edit' => Pages\EditSocialPassport::route('/{record}/edit'),
        ];
    }
}
