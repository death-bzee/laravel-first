<?php

namespace App\Filament\Resources\Concerns;

use App\Filament\Resources\Concerns\EventStatusResource\Pages;
use App\Models\Concerns\EventStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventStatusResource extends Resource
{
    use Translatable;

    protected static ?string $model = EventStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;
    protected static ?string $pluralModelLabel = 'Статусы мероприятий';
    protected static ?string $modelLabel = 'Статус мероприятия';
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
            ->defaultSort('sort')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ИД')
                    ->searchable()
                    ->sortable(),
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
            ])->headerActions([
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
            'index' => Pages\ListEventStatuses::route('/'),
            'create' => Pages\CreateEventStatus::route('/create'),
            'edit' => Pages\EditEventStatus::route('/{record}/edit'),
        ];
    }
}
