<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class TwoFactorForm extends Component
{
    public $code = '';
    public $recoveryCode = '';
    public $useRecoveryCode = false;

    public function login(): void
    {
        $this->resetErrorBag();

        if (session('two_factor.challenge') !== true) {
            $this->redirect('/login');
        }

        $user = Auth::user();

        if ($this->useRecoveryCode) {
            if (!$user->verifyRecoveryCode($this->recoveryCode)) {
                $this->addError('recoveryCode', __('The recovery code is invalid.'));
            }
        } else {
            if (!$user->verifyTwoFactorCode($this->code)) {
                $this->addError('code', __('The provided two-factor authentication code is invalid.'));
            }
        }

        session()->forget('two_factor.challenge');

        session()->regenerate();

        Auth::login($user);

        $this->redirect(route('social-passport-school'), navigate: true);
    }

    public function switchToRecoveryCode(): void
    {
        $this->useRecoveryCode = true;
        $this->reset(['code']);
    }

    public function switchToTwoFactorCode(): void
    {
        $this->useRecoveryCode = false;
        $this->reset(['recoveryCode']);
    }

    public function render(): View
    {
        return view('livewire.auth.two-factor-form');
    }
}
