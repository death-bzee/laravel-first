<div>
    <form wire:submit="login">
        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession
        <div>
            <x-label for="email" value="{{ __('Эл. почта') }}" />
            <x-input type="text" class="block mt-2 mb-2 w-full" wire:model="email" autofocus />
            <x-input-error for="email" />
        </div>
        <div class="mt-5">
            <x-label for="password" value="{{ __('Пароль') }}" />
            <x-input type="password" class="block mt-2 mb-2 w-full" wire:model="password" autofocus />
            <x-input-error for="password" />
        </div>
        <div class="block mt-5">
            <label for="remember_me" class="flex items-center">
                <x-input type="checkbox" class="rounded-sm text-indigo-600 focus:ring-indigo-500" wire:model="remember" />
                <span class="ms-3 text-sm font-medium">{{ __('Запомнить меня') }}</span>
            </label>
        </div>
        <div class="flex items-center justify-end mt-5">
            @if (Route::has('password.request'))
                <a class="text-sm text-primary hover:text-primary-light rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                   href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Забыли пароль?') }}
                </a>
            @endif
            <x-button wire:loading.attr="disabled" class="ms-4">
                <span wire:loading.remove>{{ __('Войти') }}</span>
                <span wire:loading>{{ __('Загрузка...') }}</span>
            </x-button>
        </div>
    </form>
</div>
