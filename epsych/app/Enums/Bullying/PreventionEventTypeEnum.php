<?php

namespace App\Enums\Bullying;

use Filament\Support\Contracts\HasLabel;

enum PreventionEventTypeEnum: string implements HasLabel
{
    case Info = 'info';
    case Training = 'training';

    public function getLabel(): string
    {
        return match ($this) {
            self::Info => __('Инфо'),
            self::Training => __('Повыш.комп.'),
        };
    }
}
