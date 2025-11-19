<?php

namespace App\Filament\Resources\DecreeResource\Pages;

use App\Filament\Resources\DecreeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDecree extends EditRecord
{
    protected static string $resource = DecreeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
