<?php

namespace App\Filament\Resources\Concerns\EducationLevelResource\Pages;

use App\Filament\Resources\Concerns\EducationLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEducationLevels extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }

    protected static string $resource = EducationLevelResource::class;
}
