<div>
    @if($this->phone)
        <div class="mb-8">
            <div class="mb-4 text-sm font-medium text-primary-800">
                {{ __('Если вы столкнулись с буллингом или стали его свидетелем, позвоните по номеру ниже:') }}
            </div>
            <a href="tel:{{ $this->phone }}"
               class="inline-block text-lg font-semibold text-primary-700 hover:underline">
                {{ $this->phone }}
            </a>
        </div>
    @endif

    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-10">
            <x-button>
                {{ __('Отправить сообщение') }}
            </x-button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
