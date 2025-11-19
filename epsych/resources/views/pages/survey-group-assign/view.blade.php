<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('work-plans') }}">{{ __('План работы') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Просмотр результатов выбранного теста') }}</x-h1>
            </div>
        </div>
        <livewire:tables.survey.survey-result-student-table :$id />
    </x-layouts.content-container>
</x-app-layout>
