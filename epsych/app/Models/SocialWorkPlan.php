<?php

namespace App\Models;

use App\Enums\SocialRoleEnum;
use App\Enums\TypeFormReportEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SocialWorkPlan extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'event_title',
        'execution_deadline',
        'type_responsible_person',
        'type_form_report',
    ];

    protected $casts = [
        'type' => TypeFormReportEnum::class,
        'type_responsible_person' => SocialRoleEnum::class,
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('social_pedagogue_document_form_report');
    }
}
