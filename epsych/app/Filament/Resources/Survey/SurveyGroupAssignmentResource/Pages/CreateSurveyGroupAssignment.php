<?php

namespace App\Filament\Resources\Survey\SurveyGroupAssignmentResource\Pages;

use App\Filament\Resources\Survey\SurveyGroupAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurveyGroupAssignment extends CreateRecord
{
    protected static string $resource = SurveyGroupAssignmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
