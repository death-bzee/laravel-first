<?php

namespace App\Filament\Resources\Concerns\ActivityDirectionResource\Pages;

use App\Filament\Resources\Concerns\ActivityDirectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivityDirection extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ActivityDirectionResource::class;

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
}
