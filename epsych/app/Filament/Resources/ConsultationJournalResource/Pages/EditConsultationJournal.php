<?php

namespace App\Filament\Resources\ConsultationJournalResource\Pages;

use App\Filament\Resources\ConsultationJournalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsultationJournal extends EditRecord
{
    protected static string $resource = ConsultationJournalResource::class;

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
