<x-guest-layout>
    <x-layouts.guest-container>
        <div class="flex flex-col md:flex-row md:justify-between">
            <x-h1>{{ __('Мероприятия') }}</x-h1>
        </div>
        <div>
            <livewire:tables.guest.event-table />
        </div>
    </x-layouts.guest-container>
</x-guest-layout>
