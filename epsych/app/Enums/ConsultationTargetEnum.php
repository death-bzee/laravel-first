<?php

namespace App\Enums;

use App\Models\Student;
use App\Models\Classroom;
use Filament\Support\Contracts\HasLabel;

enum ConsultationTargetEnum: string implements HasLabel
{
    case Student = 'Ученик';
    case Classroom = 'Класс';

    public function getLabel(): string
    {
        return match ($this) {
            self::Student => __('Ученик'),
            self::Classroom => __('Класс'),
        };
    }

    public function getModelClass(): ?string
    {
        return match ($this) {
            self::Student => Student::class,
            self::Classroom => Classroom::class,
        };
    }

    public static function tryFromModel(?string $modelClass): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->getModelClass() === $modelClass) {
                return $case;
            }
        }
        return null;
    }
}
