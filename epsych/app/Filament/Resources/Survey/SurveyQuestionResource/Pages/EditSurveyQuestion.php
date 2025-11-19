<?php

namespace App\Filament\Resources\Survey\SurveyQuestionResource\Pages;

use App\Filament\Resources\Survey\SurveyQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyQuestion extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = SurveyQuestionResource::class;

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
