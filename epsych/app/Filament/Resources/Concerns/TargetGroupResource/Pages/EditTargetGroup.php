<?php

namespace App\Filament\Resources\Concerns\TargetGroupResource\Pages;

use App\Filament\Resources\Concerns\TargetGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTargetGroup extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = TargetGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
			Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
