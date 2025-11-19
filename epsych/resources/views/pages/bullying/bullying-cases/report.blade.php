<x-guest-layout>
    <x-authentication-card>
        <x-h1 class="mb-6">{{ __('Сообщить о случае буллинга') }}</x-h1>

        <livewire:forms.bullying.bullying-case-form :organizationId="$organizationId" />

    </x-authentication-card>
</x-guest-layout>
