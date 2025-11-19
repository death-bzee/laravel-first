<?php

namespace App\Filament\Resources\Survey\SurveyResultResource\Pages;

use App\Filament\Resources\Survey\SurveyResultResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyResults extends ListRecords
{
    protected static string $resource = SurveyResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
