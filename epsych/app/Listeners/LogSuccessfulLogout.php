<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
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
    public function handle(Logout $event): void
    {
        if (cache()->has('logout_logged_' . $event->user->id)) {
            return;
        }

        // Сохраняем в кэш, чтобы не логировалось дважды
        cache()->put('logout_logged_' . $event->user->id, true, now()->addSeconds(2));

        $user = $event->user;
        $ip = request()->ip();

        activity('logout')
            ->causedBy($user)
            ->withProperties(['ip' => $ip])
            ->log('logout: ' . __('Пользователь вышел из системы'));
    }
}
