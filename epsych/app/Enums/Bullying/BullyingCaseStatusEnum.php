<?php

namespace App\Enums\Bullying;

use Filament\Support\Contracts\HasLabel;

enum BullyingCaseStatusEnum: string implements HasLabel
{
    case UnderReview = 'under_review';
    case Confirmed = 'confirmed';
    case Rejected = 'rejected';
    case Closed = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::UnderReview => __('На рассмотрении'),
            self::Confirmed => __('Подтверждён'),
            self::Rejected => __('Отклонён'),
            self::Closed => __('Закрыт'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::UnderReview, self::Rejected => 'danger',
            self::Confirmed => 'success',
            self::Closed => 'warning',
        };
    }
}
