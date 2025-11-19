<?php

namespace App\Filament\Resources\Bullying\BullyingCaseResource\Filters;

use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

class IncidentDateFilter
{
    /**
     * @throws Exception
     */
    public static function make(): Filter
    {
        return Filter::make('incident_date')
            ->label(__('Дата инцидента'))
            ->form([
                DatePicker::make('from')->label(__('С')),
                DatePicker::make('until')->label(__('По')),
            ])
            ->query(function ($query, array $data) {
                return $query
                    ->when($data['from'], fn($q, $date) => $q->whereDate('incident_date', '>=', $date))
                    ->when($data['until'], fn($q, $date) => $q->whereDate('incident_date', '<=', $date));
            });
    }
}
