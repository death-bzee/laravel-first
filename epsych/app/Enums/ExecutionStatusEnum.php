<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ExecutionStatusEnum: string implements HasLabel
{
    case Executed = 'Исполнено';
    case Not_Executed = 'Не исполнено';

    public function getLabel(): string
    {
        return match ($this) {
            self::Executed => __('Исполнено'),
            self::Not_Executed => __('Не исполнено'),
        };
    }
}
