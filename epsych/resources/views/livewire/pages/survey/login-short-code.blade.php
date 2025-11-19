<div>
    <x-authentication-card>
        <x-h1 class="mb-6">{{ __('Вход по коду') }}</x-h1>

        <form wire:submit.prevent="submit" class="space-y-4">
            <div>
                <x-label for="code" value="{{ __('6-значный код') }}" />
                <x-input id="code" type="text" inputmode="numeric" maxlength="6" wire:model.defer="code"
                    class="mt-1 block w-full text-center tracking-widest text-xl" placeholder="______" />
                @error('code')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <x-button type="submit" class="w-full">
                    {{ __('Войти') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</div>
