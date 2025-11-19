<div class="space-y-6">
    <div class="flex flex-col">
        <x-link href="{{ route('survey-assign') }}" wire:navigate>{{ __('Тесты и ученики') }}</x-link>
        <x-h1 class="mt-4 mb-4">
            {{ __('Результаты тестирования ученика :grade:letter класса :surname :name :patronymic', $this->studentData) }}
        </x-h1>
    </div>
    <div class="flex flex-col xl:flex-row gap-10">
        <div class="w-full order-2 md:order-1 flex-1">
            <div class="font-semibold text-gray-800 mb-6 text-lg">{{ __('Ответы ученика') }}</div>
            @foreach ($this->surveyResult as $result)
                <div class="border-b border-gray-200 last:border-b-0 pt-4 pb-4">
                    <p class="font-semibold text-gray-800">{{ $result->question->title }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $result->option->title }}</p>
                </div>
            @endforeach
        </div>
        <div class="w-full md:w-1/2 xl:w-1/3 order-1 md:order-2 shrink-0">
            <div class="bg-gray-100 p-4 md:p-6 rounded-lg">
                <div class="prose mb-4 font-bold">{!! $diagnosis->diagnosis ?? __('Диагноз ученика еще вычисляется ИИ') !!}</div>
                @if ($diagnosis)
                    <div class="prose text-sm">
                        <ul uk-accordion>
                            <li>
                                <a class="uk-accordion-title !text-primary" href>{{ __('Подробнее') }}</a>
                                <div class="uk-accordion-content">
                                    {!! $diagnosis->explained_diagnosis !!}
                                </div>
                            </li>
                            @if ($scaling)
                                <li>
                                    <a class="uk-accordion-title !text-primary" href>{{ __('Шкалирование') }}</a>
                                    <div class="uk-accordion-content">
                                        {!! $scaling !!}
                                    </div>
                                </li>
                            @endif
                            @if ($diagnosis->interpretation)
                                <li>
                                    <a class="uk-accordion-title !text-primary" href>{{ __('Интерпретация результатов') }}</a>
                                    <div class="uk-accordion-content">
                                        {!! $diagnosis->interpretation !!}
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
