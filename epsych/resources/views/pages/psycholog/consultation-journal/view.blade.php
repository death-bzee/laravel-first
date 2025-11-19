<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('work-plans') }}" wire:navigate>{{ __('План работы') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Запись журнала учета') }}</x-h1>
            </div>
        </div>
        <livewire:content.psycholog.consultation-journal-content :$record />
    </x-layouts.content-container>
</x-app-layout>
