<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Тестирование') }}</x-h1>
                <p>{!! __('Диагностическое  направление позволяет выявить особенности когнитивной, эмоционально-волевой и мотивационной сферы обучающихся посредством возможностей данной платформы. А именно исследование, анализ и оценка индивидуальных психологических особенностей обучающегося, таких как его личность, эмоциональная сфера, настроение, самооценка, оценка уровня притязаний, а также таких сфер психики, как память, внимание, мышление.
') !!}</p>
            </div>
            <x-button-link href="{{ route('survey-group-assign-create') }}">{{ __('Создать тест') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.survey.survey-group-assignment-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
