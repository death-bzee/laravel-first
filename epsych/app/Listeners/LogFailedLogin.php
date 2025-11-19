<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        activity('failed_login')
            ->withProperties([
                'email' => $event->credentials['email'] ?? null,
                'ip' => Request::ip(),
            ])
            ->log('failed_login: ' . __('Неуспешная попытка входа в систему'));
    }
}
