<?php

namespace App\Filament\Resources\LevelValueResource\Pages;

use App\Filament\Resources\LevelValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLevelValue extends EditRecord
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

    protected static string $resource = LevelValueResource::class;
}
