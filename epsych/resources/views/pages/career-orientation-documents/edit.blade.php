<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('career-orientation-documents') }}" wire:navigate>{{ __('Профориентационные документы') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Редактирование приказа') }}</x-h1>
            </div>
        </div>

        <livewire:forms.career-orientation-document-form :$record />

    </x-layouts.content-container>
</x-app-layout>
