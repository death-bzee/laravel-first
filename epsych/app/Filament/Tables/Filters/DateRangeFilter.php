<?php

namespace App\Filament\Tables\Filters;

use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class DateRangeFilter
{
    /**
     * @throws Exception
     */
    public static function make(string $column, string $label): Filter
    {
        return Filter::make($column)
            ->label($label)
            ->form([
                Grid::make(2)->schema([
                    DatePicker::make("{$column}_from")->label("{$label}: от"),
                    DatePicker::make("{$column}_until")->label("{$label}: по"),
                ]),
            ])
            ->columnSpan(2)
            ->query(function (Builder $query, array $data) use ($column): Builder {
                return $query
                    ->when($data["{$column}_from"], fn ($q) => $q->whereDate($column, '>=', $data["{$column}_from"]))
                    ->when($data["{$column}_until"], fn ($q) => $q->whereDate($column, '<=', $data["{$column}_until"]));
            });
    }
}
