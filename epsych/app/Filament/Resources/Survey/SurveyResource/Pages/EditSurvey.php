<?php

namespace App\Filament\Resources\Survey\SurveyResource\Pages;

use App\Filament\Resources\Survey\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurvey extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
