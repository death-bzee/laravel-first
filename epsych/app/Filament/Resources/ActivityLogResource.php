<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Exception;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;
    protected static ?string $pluralModelLabel = 'Журналирование';
    protected static ?string $modelLabel = 'Журналирование';
    protected static ?string $navigationGroup = 'Filament Shield';

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('causer.fullname')
                    ->label(__('Пользователь')),

                TextColumn::make('causer.role')
                    ->label(__('Роль'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('description')
                    ->label('Событие')
                    ->words(5)
                    ->sortable()
                    ->tooltip(fn ($record) => $record->description),

                TextColumn::make('properties.ip')
                    ->label(__('IP-адрес'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(__('Дата и время'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label(__('Тип события'))
                    ->options([
                        'failed_auth' => __('Неуспешная попытка входа в систему'),
                        'auth' => __('Пользователь вошел в систему'),
                        'logout' => __('Пользователь вышел из системы'),
                    ])
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function query(Builder $query): Builder
    {
        return $query->with('causer.roles'); // Загружаем пользователя и его роли (Spatie)
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
            'index' => Pages\ListActivityLogs::route('/'),
            'create' => Pages\CreateActivityLog::route('/create'),
            'edit' => Pages\EditActivityLog::route('/{record}/edit'),
        ];
    }
}
