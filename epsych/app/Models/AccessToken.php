<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AccessToken extends Model
{
    protected $fillable = ['token'];

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'access_token_organization');
    }
}
