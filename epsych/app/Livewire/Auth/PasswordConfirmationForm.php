<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class PasswordConfirmationForm extends Component
{
    public $password;

    protected $rules = [
        'password' => 'required|string|min:8',
    ];

    public function confirmPassword(): null
    {
        $this->validate();

        if (!Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('The provided password does not match your current password.')],
            ]);
        }

        session()->put('auth.password_confirmed_at', time());

        return $this->redirect(route('social-passport-school'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.password-confirmation-form');
    }
}
