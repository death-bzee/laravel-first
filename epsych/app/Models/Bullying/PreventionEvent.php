<?php

namespace App\Models\Bullying;

use App\Enums\Bullying\PreventionEventStatusEnum;
use App\Enums\Bullying\PreventionEventTypeEnum;
use App\Enums\SocialRoleEnum;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreventionEvent extends Model
{
    protected $fillable = [
        'title',
        'responsible',
        'date',
        'type',
        'status',
        'organization_id',
    ];

    protected $casts = [
        'responsible' => SocialRoleEnum::class,
        'type' => PreventionEventTypeEnum::class,
        'status' => PreventionEventStatusEnum::class,
        'date' => 'date',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
