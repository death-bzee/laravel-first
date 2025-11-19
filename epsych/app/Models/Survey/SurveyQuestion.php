<?php

namespace App\Models\Survey;

use App\Enums\Survey\SurveyQuestionTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class SurveyQuestion extends Model
{
    use HasTranslations;

    protected $fillable = ['number', 'title', 'description', 'images', 'type', 'limited_multiple_choice', 'survey_id'];

    public array $translatable = ['title', 'description'];

    protected $casts = [
        'images' => 'array',
        'type' => SurveyQuestionTypeEnum::class,
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(SurveyQuestionOption::class, 'question_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(SurveyResult::class, 'question_id');
    }

}
