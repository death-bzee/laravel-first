<?php

namespace App\Enums;

use App\Models\Survey\SurveyGroupAssignment;
use Filament\Support\Contracts\HasLabel;
use App\Models\ConsultationJournal;

enum EventTypeEnum: string implements HasLabel
{
    case Consultation = 'Консультация';
    case Testing = 'Тестирование';
    case HourPsycholog = 'Час психолога';

    public function getLabel(): string
    {
        return match ($this) {
            self::Consultation => __('Консультация'),
            self::Testing => __('Тестирование'),
            self::HourPsycholog => __('Час психолога'),
        };
    }

    public function getModelClass(): ?string
    {
        return match ($this) {
            self::Consultation => ConsultationJournal::class,
            self::Testing => SurveyGroupAssignment::class,
            self::HourPsycholog => null,
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
