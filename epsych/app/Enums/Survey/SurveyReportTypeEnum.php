<?php

namespace App\Enums\Survey;

enum SurveyReportTypeEnum: int
{
    case REGION = 1;   // Отчет по районам (вся область)
    case DISTRICT = 2; // Отчет по школам выбранного района
    case METHODIC = 3; // Отчет по методикам

    public function label(): string
    {
        return match ($this) {
            self::REGION => __('Отчет в разрезе области'),
            self::DISTRICT => __('Отчет в разрезе организации'),
            self::METHODIC => __('Отчёт по пройденным методикам'),
        };
    }


    /** Для выпадающего списка (например, <x-select2>) */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
