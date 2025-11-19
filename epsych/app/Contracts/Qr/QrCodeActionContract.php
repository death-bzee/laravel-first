<?php

namespace App\Contracts\Qr;

use Illuminate\Database\Eloquent\Model;

interface QrCodeActionContract
{
    public function handle(Model $model): void;
}
