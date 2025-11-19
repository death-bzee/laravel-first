<?php

namespace App\Filament\Resources\Concerns\ActivityDirectionResource\Pages;

use App\Filament\Resources\Concerns\ActivityDirectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateActivityDirection extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ActivityDirectionResource::class;

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
}
