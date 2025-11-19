<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TypeFormReportEnum: string implements HasLabel
{
    case SocialPassport = 'social_passport';
    case FileUpload = 'file_upload';

    public function getLabel(): string
    {
        return match ($this) {
            self::SocialPassport => __('Социальный паспорт школы'),
            self::FileUpload => __('Загрузка документа'),
        };
    }
}
