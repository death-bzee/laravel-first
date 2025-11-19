<?php

namespace App\Livewire\Components;

use App\Models\Menu;
use Livewire\Component;

class HeaderMenuComponent extends Component
{
    public $menus;

    public function mount()
    {
        $this->menus = Menu::where('is_active', true)
            ->orderBy('sort', 'asc')
            ->get();
    }
    public function render()
    {
        return view('livewire.components.header-menu-component');
    }
}
