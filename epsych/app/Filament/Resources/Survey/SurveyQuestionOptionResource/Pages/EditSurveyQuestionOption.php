<?php

namespace App\Filament\Resources\Survey\SurveyQuestionOptionResource\Pages;

use App\Filament\Resources\Survey\SurveyQuestionOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyQuestionOption extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = SurveyQuestionOptionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
