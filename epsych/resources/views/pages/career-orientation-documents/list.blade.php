<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Профориентационные документы') }}</x-h1>
            </div>
            <x-button-link href="{{ route('career-orientation-document-create') }}">{{ __('Добавить документы') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.career-orientation-document-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
