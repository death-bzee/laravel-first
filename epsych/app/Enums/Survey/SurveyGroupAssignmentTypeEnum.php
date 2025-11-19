<?php

namespace App\Enums\Survey;

use Filament\Support\Contracts\HasLabel;

enum SurveyGroupAssignmentTypeEnum: string implements HasLabel
{
    case Individual = 'individual';
    case Group = 'group';

    public function getLabel(): string
    {
        return match ($this) {
            self::Individual => __('Индивидуальный'),
            self::Group => __('Групповой'),
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
