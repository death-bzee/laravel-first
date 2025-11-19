<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TargetGroup extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'active',
        'sort',
    ];

    protected $casts = [
        'title' => 'array', // Приведение JSON к массиву
        'active' => 'boolean',
    ];

    public array $translatable = ['title'];
}
