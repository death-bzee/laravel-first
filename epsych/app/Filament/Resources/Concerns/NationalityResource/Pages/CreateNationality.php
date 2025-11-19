<?php

namespace App\Filament\Resources\Concerns\NationalityResource\Pages;

use App\Filament\Resources\Concerns\NationalityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNationality extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = NationalityResource::class;

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
