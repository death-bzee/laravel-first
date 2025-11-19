<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Мероприятия') }}</x-h1>
                <div>{!! __('<b>Коррекционно-развивающее направление</b> — это планирование и учет проведенных мероприятий направленных на помощь учащимся в их психологическом развитии и адаптации к школьной среде.') !!}</div>
            </div>
            <x-button-link href="{{ route('event-create') }}">{{ __('Добавить мероприятие') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.event-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
