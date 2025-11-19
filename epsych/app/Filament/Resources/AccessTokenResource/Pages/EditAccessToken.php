<?php

namespace App\Filament\Resources\AccessTokenResource\Pages;

use App\Filament\Resources\AccessTokenResource;
use Filament\Actions;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Pages\EditRecord;

class EditAccessToken extends EditRecord
{
    protected static string $resource = AccessTokenResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
