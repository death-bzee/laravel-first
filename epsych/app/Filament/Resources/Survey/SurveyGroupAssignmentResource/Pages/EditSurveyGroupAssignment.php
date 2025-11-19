<?php

namespace App\Filament\Resources\Survey\SurveyGroupAssignmentResource\Pages;

use App\Filament\Resources\Survey\SurveyGroupAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyGroupAssignment extends EditRecord
{
    protected static string $resource = SurveyGroupAssignmentResource::class;

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
