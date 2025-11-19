<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('decrees') }}" wire:navigate>{{ __('Приказы') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Редактирование приказа') }}</x-h1>
            </div>
        </div>

        <livewire:forms.decre-form :$record />

    </x-layouts.content-container>
</x-app-layout>
