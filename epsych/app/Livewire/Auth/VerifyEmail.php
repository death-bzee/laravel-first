<?php

namespace App\Livewire\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Component;

class VerifyEmail extends Component
{
    public $status = '';

    public function mount(): void
    {
        // Установите статус из сессии, если он доступен
        $this->status = Session::get('status', '');
    }

    public function sendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('social-passport-school'));
        }

        $request->user()->sendEmailVerificationNotification();

        // Установите статус в сессии
        Session::flash('status', 'verification-link-sent');

        // Обновите статус в компоненте
        $this->status = 'verification-link-sent';
    }

    public function logout()
    {
        Auth::logout();
        return $this->redirect('/login', navigate:true);
    }
    public function render(): View
    {
        return view('livewire.auth.verify-email');
    }
}
