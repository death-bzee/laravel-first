<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaterial extends EditRecord
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

    protected static string $resource = MaterialResource::class;
}
