@props(['id', 'name', 'accept' => 'image/png, image/jpeg, image/gif', 'tmpPhoto'])

@php
    $backgroundImage = '';
    if ($tmpPhoto && count($tmpPhoto) > 0 && in_array($tmpPhoto[0]->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
        $backgroundImage = $tmpPhoto[0]->temporaryUrl();
    }
@endphp

<div class="w-40 h-40 flex flex-col gap-2 items-center justify-center px-4 py-4 bg-gray-100 rounded-full"
     style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center;">

    @if (!$tmpPhoto)
        <label for="file-upload-{{ $id }}" class="file-upload-label">
            <i class="fi fi-sr-file-upload file-upload-icon text-primary hover:opacity-50"></i>
            <span class="hover:opacity-50 transition-all ease-in-out">{{ __('Выбрать') }}</span>
        </label>

        <input class="custom-file-input"
               id="file-upload-{{ $id }}"
               type="file"
               wire:model="{{ $name }}"
               accept="{{ $accept }}"
        />
    @endif

    <x-input-error for="{{ $name }}.*" />
</div>
