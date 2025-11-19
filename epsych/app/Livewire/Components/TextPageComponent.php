<?php

namespace App\Livewire\Components;

use App\Models\Text;
use Illuminate\View\View;
use Livewire\Component;

class TextPageComponent extends Component
{
    public ?Text $text;
    public function mount(string $slug): void
    {
        $this->text = Text::query()->where('slug', $slug)->firstOrFail();
    }

    public function render(): View
    {
        return view('livewire.components.text-page-component', [
            'text' => $this->text
        ]);
    }
}
