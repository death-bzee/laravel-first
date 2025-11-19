<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class SurveyQuestionOption extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'description', 'score', 'question_id'];

    public array $translatable = ['title', 'description']; // Переводимые поля

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(SurveyResult::class, 'option_id');
    }
}
