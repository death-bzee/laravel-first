<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout(): null
    {
        Auth::guard('web')->logout(); // Убедитесь, что используется правильный Guard
        session()->invalidate();
        session()->regenerateToken();

        return $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}
