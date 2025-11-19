<?php

namespace App\Contracts;

interface UniqueCodeServiceContract
{
    public function generateUniqueCode(): string;

    public function generateQrCode(string $uniqueCode): ?string;
}
