<?php

namespace App\Models\Survey;

use App\Jobs\Survey\ProcessSurveyAssignmentJob;
use App\Models\Concerns\Relation\AccessTokenStudentSurvey;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class SurveyAssignment extends Model
{
    protected $fillable = [
        'group_id',
        'student_id',
        'started_at',
        'assigned_at',
        'completed_at',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (is_null($model->assigned_at)) {
                $model->assigned_at = Carbon::now();
            }
        });

        static::created(function ($surveyAssignment) {
            // Передаём только ID, чтобы job не падала при удалении модели
            ProcessSurveyAssignmentJob::dispatch($surveyAssignment->id);
        });
    }

    public function accessTokenStudentSurveys(): HasMany
    {
        return $this->hasMany(AccessTokenStudentSurvey::class);
    }

    public function accessTokenCode(): HasOne
    {
        return $this->hasOne(AccessTokenStudentSurvey::class, 'survey_assignment_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(SurveyGroupAssignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function studentDiagnosis(): HasOne
    {
        return $this->hasOne(SurveyStudentDiagnosis::class, 'survey_assignment_id');
    }

    public function groupAssignment(): BelongsTo
    {
        return $this->belongsTo(SurveyGroupAssignment::class, 'group_id');
    }
}
