<?php

namespace App\Filament\Resources\LevelValueResource\Pages;

use App\Filament\Resources\LevelValueResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLevelValue extends CreateRecord
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

    protected static string $resource = LevelValueResource::class;
}
