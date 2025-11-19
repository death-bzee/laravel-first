<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ForgotPasswordForm extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|string|email|max:255',
    ];

    public function sendResetLink(): void
    {
        $this->ensureIsNotRateLimited();

        // Валидация данных
        $this->validate();

        // Отправка ссылки на сброс пароля
        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
            RateLimiter::clear($this->throttleKey());
        } else {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }

    private function ensureIsNotRateLimited(): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            event(new Lockout(request()));

            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => [trans('auth.throttle', ['seconds' => $seconds])],
            ]);
        }

        RateLimiter::hit($this->throttleKey());
    }

    private function throttleKey(): string
    {
        return Str::lower($this->email) . '|' . request()->ip();
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-form');
    }
}
