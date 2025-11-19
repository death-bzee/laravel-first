<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Fortify\Features;
use Livewire\Component;

class LoginForm extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $twoFactorChallenge = false;
    public $recoveryCode = '';
    public $twoFactorCode = '';

    protected $rules = [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ];

    public function login()
    {
        $credentials = $this->validate();

        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => [__('Too many login attempts. Please try again in :seconds seconds.', ['seconds' => $seconds])],
            ]);
        }

        if (!Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => [__('These credentials do not match our records.')],
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        if (Features::enabled(Features::twoFactorAuthentication()) && Auth::user()->two_factor_secret) {
            session()->put('login.id', Auth::id());
            session()->put('two_factor.challenge', true);

            Auth::logout();

            return redirect('/two-factor-challenge');
        }

        session()->regenerate();

        return redirect(route('social-passport-school'));
    }

    private function throttleKey(): string
    {
        return strtolower($this->email).'|'.request()->ip();
    }

    public function render(): View
    {
        return view('livewire.auth.login-form');
    }
}
