<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('План работы') }}</x-h1>
                <div>{!! __('План работы социального педагога на учебный год') !!} <x-link href="#" :navigate="false" uk-toggle="target: #social-work-plan-reference">{{ __('Справка') }}</x-link></div>
            </div>
            <x-button-link href="{{ route('social-work-plan-create') }}">{{ __('Добавить пункт плана') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.social-pedagogue.social-work-plan-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>

<x-uikit.uk-modal
    id="social-work-plan-reference"
    title="{{ __('План работы социального педагога на учебный год') }}"
    content="{{ __('social-work-plan-reference') }}"
/>
