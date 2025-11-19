<?php

namespace App\Filament\Resources\Bullying\BullyingCaseResource\Infolists;

use App\Enums\Bullying\BullyingCaseStatusEnum;
use App\Enums\RoleEnum;
use Filament\Infolists\Components\TextEntry;

class BullyingCaseInfoList
{
    public static function make(): array
    {
        return [
            TextEntry::make('victim')->label(__('Потерпевший')),
            TextEntry::make('aggressor')->label(__('Агрессор')),
            TextEntry::make('incident_date')
                ->label(__('Дата инцидента'))
                ->date('d F Y'),
            TextEntry::make('status')
                ->label(__('Статус'))
                ->formatStateUsing(fn(BullyingCaseStatusEnum $state) => $state->getLabel()),
            TextEntry::make('organization.title')->label(__('Организация')),
            TextEntry::make('role.name')
                ->label(__('Роль'))
                ->formatStateUsing(function (?string $state): string {
                    return RoleEnum::tryFrom($state)?->label() ?? $state ?? '—';
                }),
        ];
    }
}
