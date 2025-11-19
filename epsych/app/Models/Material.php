<?php

namespace App\Models;

use App\Models\Concerns\MaterialType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Material extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'text', 'images', 'videos', 'files', 'original_filenames', 'material_type_id',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'files' => 'array',
        'original_filenames' => 'array',
    ];

    public array $translatable = ['title', 'text'];

    public function materialType(): BelongsTo
    {
        return $this->belongsTo(MaterialType::class);
    }
}
