<?php

namespace App\Filament\Resources\Survey\SurveyAssignmentResource\Pages;

use App\Filament\Resources\Survey\SurveyAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyAssignments extends ListRecords
{
    protected static string $resource = SurveyAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
