<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('survey-group-assign') }}" wire:navigate>{{ __('Тестирование') }}</x-link>
                <x-h1 class="mt-4 mb-6">{{ __('Создать тест') }}</x-h1>
                <div class="text-sm text-red-500">
                    {{ __('* После создания теста, изменить можно будет только его название.') }}
                </div>
            </div>
        </div>
        <livewire:forms.survey.group-assignment-form />
        {{--<livewire:forms.survey.survey-group-assignment-form :surveyGroupAssignmentId="$id" />--}}
        {{--<livewire:forms.survey.survey-group-assignment-form />--}}
    </x-layouts.content-container>
</x-app-layout>
