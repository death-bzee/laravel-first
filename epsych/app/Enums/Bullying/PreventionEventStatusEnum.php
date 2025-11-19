<?php

namespace App\Enums\Bullying;

use Filament\Support\Contracts\HasLabel;

enum PreventionEventStatusEnum: string implements HasLabel
{
    case Planned = 'planned';
    case Completed = 'completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Planned => __('Запланировано'),
            self::Completed => __('Проведено'),
        };
    }
}
