<?php

namespace App\Filament\Resources\Bullying\BullyingCaseResource\Pages;

use App\Filament\Resources\Bullying\BullyingCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBullyingCase extends EditRecord
{
    protected static string $resource = BullyingCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
