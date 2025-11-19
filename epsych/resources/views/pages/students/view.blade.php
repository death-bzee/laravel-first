<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('students') }}" wire:navigate>{{ __('Ученики') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Просмотр сведений учащегося') }}</x-h1>
            </div>
        </div>
        <livewire:content.student.student-content :$record />
    </x-layouts.content-container>
</x-app-layout>
