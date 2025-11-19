<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Decree extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title', 'user_id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('decree_form');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
