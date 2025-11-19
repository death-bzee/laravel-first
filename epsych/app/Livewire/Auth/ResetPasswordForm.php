<?php

namespace App\Livewire\Auth;

use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ResetPasswordForm extends Component
{
    public $email;
    public $password;
    public $password_confirmation;
    public $token;

    public function mount($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function resetPassword(): null
    {
        // Подготовка данных для экшена
        $input = [
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ];

        // Вызов стандартного процесса сброса пароля
        $status = Password::reset(
            $input,
            function ($user) use ($input) {
                app(ResetUserPassword::class)->reset($user, $input);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __($status));
            return $this->redirect('/login', navigate: true);
        } else {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password-form');
    }
}
