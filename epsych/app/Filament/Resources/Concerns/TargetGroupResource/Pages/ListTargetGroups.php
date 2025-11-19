<?php

namespace App\Filament\Resources\Concerns\TargetGroupResource\Pages;

use App\Filament\Resources\Concerns\TargetGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTargetGroups extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = TargetGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
