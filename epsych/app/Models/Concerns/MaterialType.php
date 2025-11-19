<?php

namespace App\Models\Concerns;

use App\Models\Material;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class MaterialType extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'sort'];

    public array $translatable = ['title'];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }
}
