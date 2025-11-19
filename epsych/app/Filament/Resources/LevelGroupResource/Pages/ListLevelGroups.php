<?php

namespace App\Filament\Resources\LevelGroupResource\Pages;

use App\Filament\Resources\LevelGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLevelGroups extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = LevelGroupResource::class;
    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
