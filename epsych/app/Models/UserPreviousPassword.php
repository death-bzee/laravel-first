<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreviousPassword extends Model
{
    protected $table = 'user_previous_passwords';

    protected $fillable = ['user_id', 'password'];

    // Связь с пользователем
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
