<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ResponsiblePersonEnum: string implements HasLabel
{
    case Psychologist = 'psychologist';
    case SocialPedagogue = 'social_pedagogue';
    case HomeroomTeacher = 'homeroom_teacher';

    public function getLabel(): string
    {
        return match ($this) {
            self::Psychologist => __('Психолог'),
            self::SocialPedagogue => __('Социолог'),
            self::HomeroomTeacher => __('Классный руководитель'),
        };
    }
}
