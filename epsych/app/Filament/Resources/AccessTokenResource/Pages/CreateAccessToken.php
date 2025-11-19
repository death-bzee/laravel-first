<?php

namespace App\Filament\Resources\AccessTokenResource\Pages;

use App\Filament\Resources\AccessTokenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateAccessToken extends CreateRecord
{
    protected static string $resource = AccessTokenResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['token'] = Str::random(16); // Генерация токена перед сохранением
        return $data;
    }
}
