<?php

namespace App\Filament\Resources\ConsultationJournalResource\Pages;

use App\Filament\Resources\ConsultationJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateConsultationJournal extends CreateRecord
{
    protected static string $resource = ConsultationJournalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
