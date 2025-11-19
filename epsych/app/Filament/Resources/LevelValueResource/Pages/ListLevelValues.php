<?php

namespace App\Filament\Resources\LevelValueResource\Pages;

use App\Filament\Resources\LevelValueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLevelValues extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = LevelValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
