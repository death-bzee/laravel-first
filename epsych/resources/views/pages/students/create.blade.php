<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('students') }}" wire:navigate>{{ __('Ученики') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Добавить ученика') }}</x-h1>
            </div>
        </div>
        <livewire:forms.student-form />
    </x-layouts.content-container>
</x-app-layout>
