<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('work-plans') }}" wire:navigate>{{ __('План работы') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Редактирование плана') }}</x-h1>
            </div>
        </div>
        <livewire:forms.psycholog.work-plan-form :$record />
    </x-layouts.content-container>
</x-app-layout>
