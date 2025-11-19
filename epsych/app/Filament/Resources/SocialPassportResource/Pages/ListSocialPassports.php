<?php

namespace App\Filament\Resources\SocialPassportResource\Pages;

use App\Filament\Resources\SocialPassportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocialPassports extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    protected static string $resource = SocialPassportResource::class;
}
