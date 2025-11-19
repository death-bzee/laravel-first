<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('survey-group-assign') }}" wire:navigate>{{ __('Тестирование') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Изменить тест') }}</x-h1>
            </div>
        </div>
        <livewire:forms.survey.group-assignment-form :surveyGroupAssignmentId="$id" />
        {{--<livewire:forms.survey.survey-group-assignment-form :surveyGroupAssignmentId="$id" />--}}
    </x-layouts.content-container>
</x-app-layout>
