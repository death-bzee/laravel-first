<?php

namespace App\Filament\Resources\DecreeResource\Pages;

use App\Filament\Resources\DecreeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDecrees extends ListRecords
{
    protected static string $resource = DecreeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
