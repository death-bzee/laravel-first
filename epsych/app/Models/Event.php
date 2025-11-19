<?php

namespace App\Models;

use App\Models\Concerns\EventStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Event extends Model
{
    protected $fillable = ['title', 'organization_id', 'classroom_id', 'event_status_id', 'event_date'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'event_student', 'event_id', 'student_id')
                    ->withTimestamps();
    }

    public function eventStatus(): BelongsTo
    {
        return $this->belongsTo(EventStatus::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable')->where('document_group_id', 5);
    }

    protected static function booted(): void
    {
        static::deleting(function ($event) {
            $event->documents()->each(function ($document) {
                $document->delete();
            });
        });
    }
}
