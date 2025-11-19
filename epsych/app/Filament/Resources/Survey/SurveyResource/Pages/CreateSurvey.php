<?php

namespace App\Filament\Resources\Survey\SurveyResource\Pages;

use App\Filament\Resources\Survey\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurvey extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

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

    protected static string $resource = SurveyResource::class;
}
