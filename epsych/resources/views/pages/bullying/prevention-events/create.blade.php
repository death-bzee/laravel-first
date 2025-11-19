<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('bullying-prevention-events') }}" wire:navigate>{{ __('План профилактики') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Добавление плана') }}</x-h1>
            </div>
        </div>

        <livewire:forms.bullying.prevention-event-form />

    </x-layouts.content-container>
</x-app-layout>
