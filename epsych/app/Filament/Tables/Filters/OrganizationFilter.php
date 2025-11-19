<?php

namespace App\Filament\Tables\Filters;

use App\Models\Organization;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;

class OrganizationFilter
{
    /**
     * @throws Exception
     */
    public static function make(?array $organizationIds = []): Filter
    {
        return Filter::make('organization_bin')
            ->label(__('Организация'))
            ->form([
                Select::make('bin')
                    ->label(__('Выберите организацию'))
                    ->options(function () use ($organizationIds) {
                        $query = Organization::query()->orderBy('title');

                        if (!empty($organizationIds)) {
                            $query->whereIn('id', $organizationIds);
                        }

                        return $query->pluck('title', 'bin')->toArray();
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->query(function ($query, array $data) {
                return $query->when($data['bin'], function ($query, $bin) {
                    $query->whereHas('organization', fn($q) => $q->where('bin', $bin));
                });
            });
    }
}
