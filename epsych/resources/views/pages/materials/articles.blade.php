<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Статьи') }}</x-h1>
                <p>{!! __('Просветительское направление это планирование и учёт проведенных мероприятий, наличие демонстрационного и видеоматериалов для личного, профессионального роста.') !!}</p>
            </div>
        </div>
        <div>
            <livewire:tables.material-table :link="$link" type_id="1" />
        </div>
    </x-layouts.content-container>
</x-app-layout>
