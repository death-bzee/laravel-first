<?php

namespace App\Enums\Survey;

use Filament\Support\Contracts\HasLabel;

enum SurveyQuestionTypeEnum: string implements HasLabel
{
    case SingleChoice = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case LimitedMultipleChoice = 'limited_multiple_choice';
    case DropdownChoice = 'dropdown_choice';

    public function label(): string
    {
        return match ($this) {
            self::SingleChoice => __('Одиночный выбор'),
            self::MultipleChoice => __('Множественный выбор'),
            self::LimitedMultipleChoice => __('Ограниченный множественный выбор'),
            self::DropdownChoice => __('Выпадающий список'),
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::SingleChoice => __('Одиночный выбор'),
            self::MultipleChoice => __('Множественный выбор'),
            self::LimitedMultipleChoice => __('Ограниченный множественный выбор'),
            self::DropdownChoice => __('Выпадающий список'),
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], self::cases()),
            'label',
            'value'
        );
    }

    public function inputType(): string
    {
        return match ($this) {
            self::SingleChoice => 'radio',
            self::MultipleChoice, self::LimitedMultipleChoice => 'checkbox',
            self::DropdownChoice => 'select',
        };
    }
}
