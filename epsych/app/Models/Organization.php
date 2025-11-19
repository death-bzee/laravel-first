<?php

namespace App\Models;

use App\Models\Concerns\District;
use App\Models\Concerns\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Organization extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'address'];

    protected $fillable = ['bin', 'title', 'region_id', 'district_id', 'address'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    public function accessTokens(): BelongsToMany
    {
        return $this->belongsToMany(AccessToken::class, 'access_token_organization');
    }
}
