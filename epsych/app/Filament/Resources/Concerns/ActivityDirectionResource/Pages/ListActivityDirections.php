<?php

namespace App\Filament\Resources\Concerns\ActivityDirectionResource\Pages;

use App\Filament\Resources\Concerns\ActivityDirectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivityDirections extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ActivityDirectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
