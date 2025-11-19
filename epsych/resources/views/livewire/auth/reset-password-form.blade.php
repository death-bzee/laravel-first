<div>
    <form wire:submit="resetPassword">
        <input type="hidden" wire:model="token">
        @session('status')
            <div class="mb-5 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession
        <div class="block">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input type="email" class="block mt-2 mb-2 w-full" wire:model="email" autofocus />
            <x-input-error for="email" />
        </div>
		<div class="mt-4">
			<x-label for="password" value="{{ __('Password') }}" />
			<x-input type="password" class="block mt-2 mb-2 w-full" wire:model="password" />
			<x-input-error for="password" />
		</div>
		<div class="mt-4">
			<x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
			<x-input type="password" class="block mt-2 mb-2 w-full" wire:model="password_confirmation" />
            <x-input-error for="password_confirmation" />
		</div>
		<div class="flex items-center justify-end mt-4">
			<x-button wire:loading.attr="disabled">
				<span wire:loading.remove>{{ __('Reset Password') }}</span>
				<span wire:loading>{{ __('Processing...') }}</span>
            </x-button>
        </div>
    </form>
</div>
