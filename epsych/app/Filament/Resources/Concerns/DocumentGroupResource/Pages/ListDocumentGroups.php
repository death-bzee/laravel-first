<?php

namespace App\Filament\Resources\Concerns\DocumentGroupResource\Pages;

use App\Filament\Resources\Concerns\DocumentGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentGroups extends ListRecords
{
    protected static string $resource = DocumentGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
