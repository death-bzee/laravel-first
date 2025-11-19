<?php

namespace App\Filament\Resources\CareerOrientationDocumentResource\Pages;

use App\Filament\Resources\CareerOrientationDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCareerOrientationDocument extends EditRecord
{
    protected static string $resource = CareerOrientationDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
