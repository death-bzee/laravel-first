<?php

namespace App\Services;

use App\Contracts\UniqueCodeServiceContract;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class UniqueCodeService implements UniqueCodeServiceContract
{
    public function generateUniqueCode(): string
    {
        return Str::uuid();
    }

    public function generateQrCode(string $uniqueCode): ?string
    {
        // Создаём объект QR-кода
        $qrCode = new QrCode(
            data: $uniqueCode,
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        // Генерируем PNG с помощью PngWriter
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Возвращаем бинарные данные PNG
        return $result->getString();
    }
}
