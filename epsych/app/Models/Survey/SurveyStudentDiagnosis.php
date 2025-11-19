<?php

namespace App\Models\Survey;

use App\Models\LevelValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class SurveyStudentDiagnosis extends Model
{
    use hasTranslations;

    protected $fillable = ['survey_assignment_id', 'diagnosis', 'explained_diagnosis', 'scaling', 'level_value_id', 'interpretation'];

    public array $translatable = ['diagnosis', 'explained_diagnosis', 'scaling', 'interpretation'];

    /**
     * Связь с моделью SurveyAssignment
     */
    public function surveyAssignment(): BelongsTo
    {
        return $this->belongsTo(SurveyAssignment::class, 'survey_assignment_id');
    }

    public function levelValue(): BelongsTo
    {
        return $this->belongsTo(LevelValue::class, 'level_value_id');
    }

}
