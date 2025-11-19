<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;

    protected $fillable = ['parent_id','title','icon','link','sort', 'roles'];

    public array $translatable = ['title'];

    protected $casts = [
        'roles' => 'array', // Автоматическая конвертация JSON в массив
    ];
}
