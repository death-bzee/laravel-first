<div>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex gap-4 col-span-full mt-10">
            <x-button>
                {{ __('Сохранить') }}
            </x-button>
            <x-button-link href="{{ route('career-orientation-documents') }}" styleBtn="secondary">{{ __('Отмена') }}</x-button-link>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
