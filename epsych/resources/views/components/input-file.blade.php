@props(['id', 'name', 'accept' => null])

<div class="mt-6">

	<div class="mt-4 mb-4" wire:loading wire:target="{{ $name }}">{{ __('Идет загрузка ...') }}</div>

	<label for="file-upload-{{ $id }}" class="file-upload-label mb-2">
  		<i class="fi fi-sr-file-upload file-upload-icon text-primary hover:opacity-50"></i> <span class="hover:opacity-50 transition-all ease-in-out">{{ __('Загрузить файлы') }}</span>
	</label>
	<input class="custom-file-input"
	   id="file-upload-{{ $id }}"
	   type="file"
	   multiple
	   wire:model="{{ $name }}"
	   accept="{{ $accept }}"
	/>

</div>
