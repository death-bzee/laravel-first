<?php

namespace App\Livewire\Components\Qr;

use Illuminate\View\View;
use Livewire\Component;

class QrScannerComponent extends Component
{
    public function render(): View
    {
        return view('livewire.components.qr.qr-scanner-component');
    }
}
