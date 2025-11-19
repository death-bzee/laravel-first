<?php

namespace App\Models;

use App\Models\Concerns\DocumentGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'original_name',
        'file_size',
        'file_extension',
        'documentable_id',
        'document_group_id',
        'documentable_type'
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function documentGroup(): BelongsTo
    {
        return $this->belongsTo(DocumentGroup::class);
    }
}
