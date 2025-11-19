<?php

namespace App\Filament\Tables\Filters;

use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class TextInputFilter
{
    /**
     * @throws Exception
     */
    public static function make(string $column, ?string $label = null): Filter
    {
        return Filter::make($column)
            ->label($label ?? ucfirst($column))
            ->form([
                TextInput::make($column)
                    ->label($label ?? ucfirst($column)),
            ])
            ->query(function (Builder $query, array $data) use ($column): Builder {
                return $query->when(
                    $data[$column] ?? null,
                    fn (Builder $query, $value) =>
                        $query->whereRaw("LOWER($column) LIKE ?", ['%' . mb_strtolower($value) . '%'])
                );
            });
    }

}
