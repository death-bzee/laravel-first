<?php

namespace App\Filament\Resources\SocialPassportResource\Pages;

use App\Filament\Resources\SocialPassportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSocialPassport extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = SocialPassportResource::class;

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
}
