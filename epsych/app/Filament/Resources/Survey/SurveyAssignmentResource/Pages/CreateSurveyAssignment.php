<?php

namespace App\Filament\Resources\Survey\SurveyAssignmentResource\Pages;

use App\Filament\Resources\Survey\SurveyAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSurveyAssignment extends CreateRecord
{
    protected static string $resource = SurveyAssignmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
