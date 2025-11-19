<x-app-layout>
    <x-layouts.content-container>
        <div class="flex justify-between">
            <div class="flex flex-col">
                <x-link href="{{ route('social-work-plans') }}" wire:navigate>{{ __('План работы') }}</x-link>
                <x-h1 class="mt-4 mb-0">{{ __('Добавление плана') }}</x-h1>
            </div>
        </div>
        <livewire:forms.social-pedagogue.social-work-plan-form />
    </x-layouts.content-container>
</x-app-layout>
