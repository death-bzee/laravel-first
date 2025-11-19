<?php

namespace App\Filament\Resources\Survey\SurveyQuestionOptionResource\Pages;

use App\Filament\Resources\Survey\SurveyQuestionOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurveyQuestionOption extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = SurveyQuestionOptionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
