<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {

        if (cache()->has('login_logged_' . $event->user->id)) {
            return;
        }

        // Сохраняем в кэш, чтобы не логировалось дважды
        cache()->put('login_logged_' . $event->user->id, true, now()->addSeconds(2));

        $user = $event->user;
        $ip = request()->ip();

        activity('login')
            ->causedBy($user)
            ->withProperties(['ip' => $ip])
            ->log('login: ' . __('Пользователь вошел в систему'));
    }
}
