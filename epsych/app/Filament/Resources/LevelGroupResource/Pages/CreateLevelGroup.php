<?php

namespace App\Filament\Resources\LevelGroupResource\Pages;

use App\Filament\Resources\LevelGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLevelGroup extends CreateRecord
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

    protected static string $resource = LevelGroupResource::class;
}
