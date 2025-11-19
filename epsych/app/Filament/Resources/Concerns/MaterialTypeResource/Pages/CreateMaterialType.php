<?php

namespace App\Filament\Resources\Concerns\MaterialTypeResource\Pages;

use App\Filament\Resources\Concerns\MaterialTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterialType extends CreateRecord
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

    protected static string $resource = MaterialTypeResource::class;
}
