<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ActivityDirection extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'active',
        'sort',
    ];

    protected $casts = [
        'title' => 'array',
        'active' => 'boolean',
    ];

    public array $translatable = ['title'];
}
