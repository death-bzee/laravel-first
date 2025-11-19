<?php

namespace App\Filament\Resources\TextResource\Pages;

use App\Filament\Resources\TextResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTexts extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = TextResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
