<?php

namespace App\Filament\Resources\Survey\SurveyGroupAssignmentResource\Pages;

use App\Filament\Resources\Survey\SurveyGroupAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyGroupAssignments extends ListRecords
{
    protected static string $resource = SurveyGroupAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
