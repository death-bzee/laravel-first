<?php

namespace App\Filament\Resources\Concerns\DocumentGroupResource\Pages;

use App\Filament\Resources\Concerns\DocumentGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentGroup extends EditRecord
{
    protected static string $resource = DocumentGroupResource::class;

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
