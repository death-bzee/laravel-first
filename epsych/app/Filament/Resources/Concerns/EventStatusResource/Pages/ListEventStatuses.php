<?php

namespace App\Filament\Resources\Concerns\EventStatusResource\Pages;

use App\Filament\Resources\Concerns\EventStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventStatuses extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    protected static string $resource = EventStatusResource::class;
}
