<?php

namespace App\Contracts\User;

use App\Models\User;

interface UserPasswordExpirationServiceContract
{
    public function isPasswordExpired(User $user): bool;
}
