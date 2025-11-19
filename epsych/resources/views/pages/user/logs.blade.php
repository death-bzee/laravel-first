<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Журнал входов/выходов') }}</x-h1>
            </div>
        </div>
        <div>
            <livewire:tables.activity-log-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
