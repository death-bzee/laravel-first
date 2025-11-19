<?php

namespace App\Services;

use App\Models\QrCode;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrCodeService
{
    public function generateQrCodeRecord(
        Model $model,
        array $meta = [],
        ?\DateTimeInterface $expiresAt = null
    ): QrCode
    {
        return QrCode::query()->updateOrCreate(
            [
                'qr_codeable_type' => $model::class,
                'qr_codeable_id' => $model->getKey(),
            ],
            [
                'uuid' => Str::uuid(),
                'meta' => $meta,
                'expires_at' => $expiresAt,
            ]
        );
    }

    public function getModelQrCode(string $uuid): ?Model
    {
        return QrCode::query()
            ->where('uuid', $uuid)
            ->first()?->qrCodeable;
    }

    public function getUuidQrCode(Model $model): ?string
    {
        return QrCode::query()
            ->where('qr_codeable_type', $model::class)
            ->where('qr_codeable_id', $model->getKey())
            ->value('uuid');
    }

    public function generateQrCodeImage(string $data): ?string
    {
        $qrCode = new EndroidQrCode(
            data: $data,
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $writer = new PngWriter;
        $result = $writer->write($qrCode);

        return $result->getString(); // PNG binary
    }

    public function streamQrResponse(string $qrCodeImage, string $filename): StreamedResponse
    {
        return response()->stream(function () use ($qrCodeImage) {
            echo $qrCodeImage;
        }, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'public',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Length' => strlen($qrCodeImage),
        ]);
    }

}
