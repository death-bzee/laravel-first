<?php

namespace App\Filament\Resources\Concerns\EventStatusResource\Pages;

use App\Filament\Resources\Concerns\EventStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventStatus extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }

    protected static string $resource = EventStatusResource::class;
}
