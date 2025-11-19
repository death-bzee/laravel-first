<?php

namespace App\Models;

use App\Enums\LevelGroupTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LevelGroup extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'type'];

    public array $translatable = ['title']; // Указываем, что title переводимый

    // Устанавливаем каст для enum
    protected $casts = [
        'type' => LevelGroupTypeEnum::class,
    ];

    public function values()
    {
        return $this->hasMany(LevelValue::class);
    }
}
