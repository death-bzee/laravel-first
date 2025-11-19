<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('survey-group-assign') }}" wire:navigate>{{ __('Тестирование') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Статистика тестирования') }}</x-h1>
            </div>
        </div>
        <livewire:content.survey.statistics-content :id="$id" />
    </x-layouts.content-container>
</x-app-layout>
