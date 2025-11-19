<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class LevelValue extends Model
{
    use HasTranslations;

    protected $fillable = ['code', 'value', 'title', 'level_group_id'];

    public array $translatable = ['title'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(LevelGroup::class, 'level_group_id');
    }
}
