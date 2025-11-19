<?php

namespace App\Filament\Resources\Concerns\EducationLevelResource\Pages;

use App\Filament\Resources\Concerns\EducationLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEducationLevel extends CreateRecord
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

    protected static string $resource = EducationLevelResource::class;
}
