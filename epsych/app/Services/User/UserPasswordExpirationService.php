<?php

namespace App\Services\User;

use App\Contracts\User\UserPasswordExpirationServiceContract;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class UserPasswordExpirationService implements UserPasswordExpirationServiceContract
{
    protected int $expirationDays;

    public function __construct()
    {
        $this->expirationDays = Config::get('auth.passwords.users.password_expiration_days', 90);
    }

    public function isPasswordExpired(User $user): bool
    {
        return !$user->password_changed_at || Carbon::parse($user->password_changed_at)->addDays($this->expirationDays)->isPast();
    }
}
