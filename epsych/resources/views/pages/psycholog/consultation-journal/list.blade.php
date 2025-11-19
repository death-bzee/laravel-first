<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Журнал учета') }}</x-h1>
                <div>{!! __('Журнал учета консультациий педагога-психолога') !!}</div>
            </div>
            <x-button-link href="{{ route('consultation-journal-create') }}">{{ __('Добавить запись учета') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.psycholog.consultation-journal-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
