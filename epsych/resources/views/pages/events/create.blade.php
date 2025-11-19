<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <a href="{{ route('events') }}" wire:navigate>{{ __('Мероприятия') }}</a>
                <x-h1 class="mt-4 mb-0">{{ __('Добавить мероприятие') }}</x-h1>
            </div>
        </div>
        <livewire:forms.event-form />
    </x-layouts.content-container>
</x-app-layout>
