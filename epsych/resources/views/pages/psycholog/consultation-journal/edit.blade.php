<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('consultation-journals') }}">{{ __('Журнал учета консультациий педагога-психолога') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Редактирование записи учета') }}</x-h1>
            </div>
        </div>
        <livewire:forms.psycholog.consultation-journal-form :$record />
    </x-layouts.content-container>
</x-app-layout>
