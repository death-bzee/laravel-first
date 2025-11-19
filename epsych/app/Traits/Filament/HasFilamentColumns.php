<?php

namespace App\Traits\Filament;

use Filament\Tables\Columns\TextColumn;

trait HasFilamentColumns
{
    private static function getCreationColumns(): array
    {
        return [
            TextColumn::make('created_at')
                ->label(__('Дата создания'))
                ->dateTime('d F Y H:i:s')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->label(__('Дата обновления'))
                ->dateTime('d F Y H:i:s')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
