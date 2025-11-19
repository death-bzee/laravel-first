<?php

namespace App\Models\Concerns;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['regionCode', 'title', 'sort'];

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
