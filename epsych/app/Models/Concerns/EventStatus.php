<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class EventStatus extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title'];

    public $translatable = ['title'];
}
