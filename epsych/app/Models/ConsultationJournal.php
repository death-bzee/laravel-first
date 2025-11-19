<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ConsultationJournal extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'date',
        'user_id',
        'student_id',
        'request',
        'recommendations',
        'notes',
        'consultant',
        'comment',
        'consultable_type',
        'consultable_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function consultable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function title(): Attribute
    {
        return Attribute::get(fn() => "{$this->student?->fullName} ({$this->date})");
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('psycholog_request_documents');
    }
}
