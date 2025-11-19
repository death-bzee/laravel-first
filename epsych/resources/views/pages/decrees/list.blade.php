<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Приказы') }}</x-h1>
                <div>{!! __('Первый этап (Подготовительный)') !!} <x-link href="#" :navigate="false" uk-toggle="target: #decrees-reference">{{ __('Справка') }}</x-link></div>
            </div>
            <x-button-link href="{{ route('decree-create') }}">{{ __('Добавить приказ') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.decree-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>

<x-uikit.uk-modal
    id="decrees-reference"
    title="{{ __('Приказ') }}"
    content="{{ __('decrees-reference') }}"
/>
