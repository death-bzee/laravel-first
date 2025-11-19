<?php

namespace App\Filament\Resources\Concerns;

use App\Filament\Resources\Concerns\MaterialTypeResource\Pages;
use App\Models\Concerns\MaterialType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaterialTypeResource extends Resource
{
    use Translatable;

    protected static ?string $model = MaterialType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 6;
    protected static ?string $pluralModelLabel = 'Типы материалов';
    protected static ?string $modelLabel = 'Тип материала';
    protected static ?string $navigationGroup = 'Выпадающие списки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ->columns([
                Tables\Columns\TextInputColumn::make('title')
                    ->label('Заголовок')
                    ->rules(['required', 'max:255'])
                    ->sortable(),
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
            'index' => Pages\ListMaterialTypes::route('/'),
            'create' => Pages\CreateMaterialType::route('/create'),
            'edit' => Pages\EditMaterialType::route('/{record}/edit'),
        ];
    }
}
