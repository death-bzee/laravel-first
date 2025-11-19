<?php

namespace App\Filament\Resources\Concerns\DocumentGroupResource\Pages;

use App\Filament\Resources\Concerns\DocumentGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDocumentGroup extends CreateRecord
{
    protected static string $resource = DocumentGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
