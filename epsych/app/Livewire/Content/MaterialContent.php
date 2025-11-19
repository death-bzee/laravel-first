<?php

namespace App\Livewire\Content;

use App\Contracts\MaterialServiceContract;
use App\Models\Material;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Livewire\Component;

class MaterialContent extends Component
{
    public ?int $id = null;
    public ?string $link = null;
    public $videos;
    public $files;
    public ?Material $material = null;

    public function mount($link, $id): void
    {
        $id = (int) $id;

        // Очищаем и фильтруем `link`
        $validated = Validator::make(
            ['link' => $link],
            ['link' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\-_]+$/']]
        )->validate();

        $this->link = e($validated['link']);

        $materialService = app(MaterialServiceContract::class);
        $this->material = Material::find($id) ?? new Material();
        $this->videos = $materialService->processVideos($this->material->videos ?? []);
        $this->files = $materialService->processFiles($this->material->files ?? []);
    }

    public function render(): View
    {
        return view('livewire.content.material-content');
    }

}
