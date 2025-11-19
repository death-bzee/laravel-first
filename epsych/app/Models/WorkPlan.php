<?php

namespace App\Models;

use App\Enums\EventTypeEnum;
use App\Enums\ResponsiblePersonEnum;
use App\Models\Concerns\ActivityDirection;
use App\Models\Concerns\TargetGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WorkPlan extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'type_event',
        'activity_direction_id',
        'target_group',
        'item_number',
        'activity_direction_other',
        'execution_deadline',
        'completion_form',
        'responsible_person',
        'execution_status',
        'comment',
        'work_planable_type',
        'work_planable_id',
    ];

    protected $casts = [
        'event_type' => EventTypeEnum::class,
        'responsible_person' => ResponsiblePersonEnum::class,
    ];

    public function activityDirection(): BelongsTo
    {
        return $this->belongsTo(ActivityDirection::class, 'activity_direction_id');
    }

    public function targetGroup(): BelongsTo
    {
        return $this->belongsTo(TargetGroup::class, 'target_group_id');
    }

    public function workPlanable(): MorphTo
    {
        return $this->morphTo();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('psycholog_completion_form');
        $this->addMediaCollection('psycholog_execution_note_form');
    }
}
