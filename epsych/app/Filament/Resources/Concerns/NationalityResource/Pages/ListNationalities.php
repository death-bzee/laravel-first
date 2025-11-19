<?php

namespace App\Filament\Resources\Concerns\NationalityResource\Pages;

use App\Filament\Resources\Concerns\NationalityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNationalities extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = NationalityResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
