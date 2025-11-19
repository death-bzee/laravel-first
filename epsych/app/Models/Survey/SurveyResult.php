<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SurveyResult extends Model
{
    protected $fillable = [
        'survey_assignment_id',
        'question_id',
        'option_id',
        'morphable_id',
        'morphable_type',
    ];

    public function surveyAssignment(): BelongsTo
    {
        return $this->belongsTo(SurveyAssignment::class, 'survey_assignment_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestionOption::class, 'option_id');
    }

    public function morphable(): MorphTo
    {
        return $this->morphTo();
    }
}
