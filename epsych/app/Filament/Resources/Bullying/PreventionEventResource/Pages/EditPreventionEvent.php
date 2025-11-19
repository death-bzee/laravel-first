<?php

namespace App\Filament\Resources\Bullying\PreventionEventResource\Pages;

use App\Filament\Resources\Bullying\PreventionEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreventionEvent extends EditRecord
{
    protected static string $resource = PreventionEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
