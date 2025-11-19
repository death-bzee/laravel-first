<?php

namespace App\Filament\Resources\ConsultationJournalResource\Pages;

use App\Filament\Resources\ConsultationJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultationJournals extends ListRecords
{
    protected static string $resource = ConsultationJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
