<?php

namespace App\Filament\Resources\Concerns\LanguageResource\Pages;

use App\Filament\Resources\Concerns\LanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLanguages extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    protected static string $resource = LanguageResource::class;
}
