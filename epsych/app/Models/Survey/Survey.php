<?php

namespace App\Models\Survey;

use App\Models\LevelGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Survey extends Model
{
    use HasTranslations;

    protected $fillable = [
		'title',
		'description',
		'images',
		'interpretation',
		'scaling_prompt',
		'interpretation_prompt',
		'has_level_group',
		'level_group_id',
		'sort'
	];

    public array $translatable = ['title', 'description']; // Переводимые поля

    protected $casts = [
        'images' => 'array',
    ];

    public function groups(): HasMany
    {
        return $this->hasMany(SurveyGroupAssignment::class, 'survey_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(SurveyAssignment::class);
    }

    public function levelGroup(): BelongsTo
    {
        return $this->belongsTo(LevelGroup::class, 'level_group_id');
    }
}
