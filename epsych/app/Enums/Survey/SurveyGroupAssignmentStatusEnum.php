<?php

namespace App\Enums\Survey;

use Filament\Support\Contracts\HasLabel;

enum SurveyGroupAssignmentStatusEnum: string implements HasLabel
{
    case Pending = 'pending';
    case Started = 'started';
    case Completed = 'completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => __('Ожидает запуска'),
            self::Started => __('Запущен'),
            self::Completed => __('Завершен'),
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn($case) => ['value' => $case->value, 'label' => $case->getLabel()], self::cases()),
            'label',
            'value'
        );
    }
}
