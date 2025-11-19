<?php

namespace App\Models\Concerns;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentGroup extends Model
{
    use HasFactory;

    protected $fillable = ['title','sort'];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
