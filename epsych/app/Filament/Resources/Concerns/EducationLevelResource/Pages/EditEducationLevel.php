<?php

namespace App\Filament\Resources\Concerns\EducationLevelResource\Pages;

use App\Filament\Resources\Concerns\EducationLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEducationLevel extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected static string $resource = EducationLevelResource::class;
}
