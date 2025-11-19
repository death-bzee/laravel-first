<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <x-h1>{{ __('Результаты тестов') }}</x-h1>
            <x-button-link href="{{ route('survey-assign-create') }}">{{ __('Назначить тест') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.survey.survey-assignment-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
