<?php

namespace App\Filament\Resources\Concerns\TargetGroupResource\Pages;

use App\Filament\Resources\Concerns\TargetGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTargetGroup extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = TargetGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
