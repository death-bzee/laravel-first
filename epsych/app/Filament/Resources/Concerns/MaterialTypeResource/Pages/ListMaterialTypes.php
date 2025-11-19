<?php

namespace App\Filament\Resources\Concerns\MaterialTypeResource\Pages;

use App\Filament\Resources\Concerns\MaterialTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaterialTypes extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    protected static string $resource = MaterialTypeResource::class;
}
