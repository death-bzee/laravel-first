<?php

namespace App\Filament\Resources\Survey\SurveyQuestionResource\Pages;

use App\Filament\Resources\Survey\SurveyQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyQuestions extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = SurveyQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
