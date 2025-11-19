<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('План работы') }}</x-h1>
                <div>{!! __('План работы педагога-психолога на учебный год') !!} <x-link href="#" :navigate="false" uk-toggle="target: #psycholog-work-plan-reference">{{ __('Справка') }}</x-link></div>
            </div>
            <x-button-link href="{{ route('work-plan-create') }}">{{ __('Добавить пункт плана') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.psycholog.work-plan-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>

<x-uikit.uk-modal
    id="psycholog-work-plan-reference"
    title="{{ __('План работы педагога-психолога') }}"
    content="{{ __('psycholog-work-plan-reference') }}"
/>
