<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Language extends Model
{
    use HasTranslations;

    protected $fillable = ['title','sort'];

    public $translatable = ['title'];
}
