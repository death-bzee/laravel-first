<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('survey-assign') }}" wire:navigate>{{ __('Результаты тестов') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Назначить тест для ученика') }}</x-h1>
            </div>
        </div>
        <livewire:forms.survey.assignment-form />
    </x-layouts.content-container>
</x-app-layout>
