<?php

namespace App\Filament\Resources\Bullying\BullyingCaseResource\Pages;

use App\Filament\Resources\Bullying\BullyingCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBullyingCases extends ListRecords
{
    protected static string $resource = BullyingCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
