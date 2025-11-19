<?php

namespace App\Contracts;

use App\Models\QrCode;
use Illuminate\Database\Eloquent\Model;

interface QrCodeServiceContract
{
    public function generateQrCodeRecord(
        Model               $model,
        array               $meta = [],
        ?\DateTimeInterface $expiresAt = null
    ): QrCode;

    public function getUuidQrCode(Model $model): ?string;

    public function getModelQrCode(string $uuid): ?Model;

    public function generateQrCodeImage(string $data): ?string;
}
