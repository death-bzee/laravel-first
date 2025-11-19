<?php

namespace App\Models\Survey;

use App\Contracts\QrCodeServiceContract;
use App\Enums\Survey\SurveyGroupAssignmentStatusEnum;
use App\Enums\Survey\SurveyGroupAssignmentTypeEnum;
use App\Models\Classroom;
use App\Models\Organization;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SurveyGroupAssignment extends Model
{
    protected $fillable = [
        'title',
        'organization_id',
        'classroom_id',
        'survey_id',
        'unique_code',
        'type',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'questions_count'
    ];

    protected $casts = [
        'type' => SurveyGroupAssignmentTypeEnum::class,
        'status' => SurveyGroupAssignmentStatusEnum::class,
    ];

    protected static function booted(): void
    {
        static::created(function (SurveyGroupAssignment $model) {
            app(QrCodeServiceContract::class)->generateQrCodeRecord($model);
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,
            SurveyAssignment::class,
            'group_id',  // Foreign key в SurveyAssignment
            'id',        // Local key в Student
            'id',        // Local key в SurveyGroupAssignment
            'student_id' // Foreign key в Student
        );
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(SurveyAssignment::class, 'group_id');
    }

    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(SurveyQuestion::class, Survey::class, 'id', 'survey_id', 'survey_id', 'id');
    }
}
