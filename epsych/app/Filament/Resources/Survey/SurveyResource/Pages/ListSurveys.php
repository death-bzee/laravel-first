<?php

namespace App\Filament\Resources\Survey\SurveyResource\Pages;

use App\Filament\Resources\Survey\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveys extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = SurveyResource::class;
    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
