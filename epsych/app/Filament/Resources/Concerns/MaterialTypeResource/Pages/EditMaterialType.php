<?php

namespace App\Filament\Resources\Concerns\MaterialTypeResource\Pages;

use App\Filament\Resources\Concerns\MaterialTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaterialType extends EditRecord
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

    protected static string $resource = MaterialTypeResource::class;

}
