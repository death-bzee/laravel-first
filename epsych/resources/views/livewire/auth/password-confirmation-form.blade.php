<div>
    <form wire:submit="confirmPassword">
        <div>
            <x-label for="password" value="{{ __('Password') }}" />
            <x-input type="password" class="block mt-1 w-full" wire:model="password" autofocus />
            <x-input-error for="password" />
        </div>
        <div class="flex justify-end mt-4">
            <x-button>
                <span wire:loading.remove>{{ __('Confirm') }}</span>
                <span wire:loading>{{ __('Processing...') }}</span>
            </x-button>
        </div>
    </form>
</div>
