<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SocialRoleEnum: string implements HasLabel
{
    case HomeroomTeacher = 'homeroom_teacher';
    case Psychologist = 'psychologist';
    //case StudentParent = 'student_parent';
    case HeadTeacher = 'head_teacher'; // добавленный "завуч"


    public function getLabel(): string
    {
        return match ($this) {
            self::HomeroomTeacher => __('Преподаватель'),
            self::Psychologist => __('Психолог'),
            //self::StudentParent => __('Родитель'),
            self::HeadTeacher => __('Заведующий по вр'),
        };
    }
}
