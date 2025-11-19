<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <a href="{{ route('events') }}" wire:navigate>{{ __('Мероприятия') }}</a>
                <x-h1 class="mt-4 mb-0">{{ __('Редактирование данных мероприятия') }}</x-h1>
            </div>
        </div>
        <livewire:forms.event-form :eventId="$id" />
    </x-layouts.content-container>
</x-app-layout>
