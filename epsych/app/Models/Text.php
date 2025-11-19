<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Text extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'text',
        'slug',
        'active',
        'sort',
    ];

    public array $translatable = [
        'title',
        'text',
    ];
}
