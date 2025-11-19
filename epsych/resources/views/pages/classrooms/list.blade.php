<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-4">
            <div>
                <x-h1 class="mb-0">{{ __('Классы') }}</x-h1>
            </div>
        </div>
        <div>
            <livewire:tables.student.classroom-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
