<x-guest-layout>
    <x-authentication-card>

        <x-validation-errors class="mb-4" />

        <x-h1 class="mb-12">{{ __('Войти в систему') }}</x-h1>

        <livewire:auth.login-form />

    </x-authentication-card>
</x-guest-layout>
