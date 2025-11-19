<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <a href="{{ route('survey-assign') }}" wire:navigate>{{ __('Результаты тестов') }}</a>
                <x-h1 class="mt-4 mb-0">{{ __('Изменить назначение теста для ученика') }}</x-h1>
            </div>
        </div>
        <livewire:forms.survey.assignment-form :surveyAssignmentId="$id" />
    </x-layouts.content-container>
</x-app-layout>
