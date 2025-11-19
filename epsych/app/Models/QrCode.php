<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class QrCode extends Model
{
    protected $fillable = [
        'uuid',
        'qr_codeable_type',
        'qr_codeable_id',
        'meta',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function qrCodeable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function generateFor(Model $model, array $meta = [], ?\DateTimeInterface $expiresAt = null): static
    {
        return static::create([
            'uuid' => \Str::uuid(),
            'qr_codeable_type' => $model::class,
            'qr_codeable_id' => $model->getKey(),
            'meta' => $meta,
            'expires_at' => $expiresAt,
        ]);
    }
}
