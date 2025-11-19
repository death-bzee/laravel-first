<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <livewire:auth.reset-password-form :token="$request->route('token')" :email="$request->email" />

    </x-authentication-card>
</x-guest-layout>
