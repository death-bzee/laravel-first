<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('План профилактики') }}</x-h1>
            </div>
            <x-button-link href="{{ route('bullying-prevention-event-create') }}">{{ __('Добавить план') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.bullying.prevention-event-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
