<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Nationality extends Model
{
    use HasTranslations;

    protected $fillable = ['active', 'title', 'sort'];
    public array $translatable = ['title'];
}
