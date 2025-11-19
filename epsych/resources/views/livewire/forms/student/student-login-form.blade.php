<div>
    <form wire:submit="login">
        <div class="mt-5">
            <x-label for="access_code" value="{{ __('Введите шестизначный код') }}" />
            <x-input type="text" class="block mt-2 mb-2 w-full" wire:model="access_code" x-mask="999-999" placeholder="000-000" />
            <x-input-error for="access_code" />
        </div>
        <div class="flex items-center justify-end mt-5">
            <x-button wire:loading.attr="disabled" class="ms-4">
                <span wire:loading.remove>{{ __('Войти') }}</span>
                <span wire:loading>{{ __('Загрузка...') }}</span>
            </x-button>
        </div>
    </form>
</div>
