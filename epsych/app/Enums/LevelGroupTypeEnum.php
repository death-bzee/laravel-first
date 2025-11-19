<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum LevelGroupTypeEnum: string implements HasLabel
{
    case Risk = 'risk';
    case Motivation = 'motivation';

    public function getLabel(): string
    {
        return match ($this) {
            self::Risk => __('Группа риска'),
            self::Motivation => __('Группа мотивация'),
        };
    }
}
