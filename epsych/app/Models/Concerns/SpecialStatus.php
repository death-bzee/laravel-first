<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SpecialStatus extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'sort'];

    public array $translatable = ['title'];
}
