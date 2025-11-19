<?php

namespace App\Filament\Resources\SocialPassportResource\Pages;

use App\Filament\Resources\SocialPassportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialPassport extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected static string $resource = SocialPassportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
