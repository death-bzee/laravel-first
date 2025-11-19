<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Отчет по пройденным методикам') }}</x-h1>
            </div>
        </div>
        <div>
            <livewire:components.report-table-component />
        </div>
    </x-layouts.content-container>
</x-app-layout>


{{--
<x-uikit.uk-modal
    id="students-reference"
    title="{{ __('Сведения об учащихся школы ') }}"
    content="{{ __('students-reference') }}"
/>
--}}
