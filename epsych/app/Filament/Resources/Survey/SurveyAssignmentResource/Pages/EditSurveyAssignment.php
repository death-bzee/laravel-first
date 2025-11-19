<?php

namespace App\Filament\Resources\Survey\SurveyAssignmentResource\Pages;

use App\Filament\Resources\Survey\SurveyAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyAssignment extends EditRecord
{
    protected static string $resource = SurveyAssignmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
