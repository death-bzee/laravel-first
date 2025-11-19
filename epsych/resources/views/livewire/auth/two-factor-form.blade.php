<div>
    <form wire:submit="login">
        <div class="mt-4" x-show="!useRecoveryCode">
            <x-label for="code" value="{{ __('Code') }}" />
            <x-input id="code" class="block mt-1 w-full" type="text" inputmode="numeric" wire:model="code" autofocus />
            <x-input-error for="code" />
        </div>
        <div class="mt-4" x-cloak x-show="useRecoveryCode">
            <x-label for="recovery_code" value="{{ __('Recovery Code') }}" />
            <x-input id="recovery_code" class="block mt-1 w-full" type="text" wire:model="recoveryCode" />
            <x-input-error for="recoveryCode" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                    x-show="!useRecoveryCode"
                    wire:click="switchToRecoveryCode">
                {{ __('Use a recovery code') }}
            </button>
            <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                    x-cloak
                    x-show="useRecoveryCode"
                    wire:click="switchToTwoFactorCode">
                {{ __('Use an authentication code') }}
            </button>
            <x-button class="ms-4">
                {{ __('Log in') }}
            </x-button>
        </div>
    </form>
</div>
