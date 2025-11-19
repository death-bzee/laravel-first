<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CareerOrientationDocument extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title', 'organization_id'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('сareer_orientation_document');
    }
}
