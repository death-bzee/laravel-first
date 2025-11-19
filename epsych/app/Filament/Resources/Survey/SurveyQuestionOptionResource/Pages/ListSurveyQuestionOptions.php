<?php

namespace App\Filament\Resources\Survey\SurveyQuestionOptionResource\Pages;

use App\Filament\Resources\Survey\SurveyQuestionOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyQuestionOptions extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = SurveyQuestionOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
