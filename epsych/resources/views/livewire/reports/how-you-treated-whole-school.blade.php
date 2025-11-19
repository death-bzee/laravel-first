@if ($reportTitle)
    <h2 class="text-xl font-bold mb-4 text-center">
        {{ __($reportTitle) }}
    </h2>
@endif

<div class="overflow-x-auto">

    <table class="min-w-full border text-xs">

        {{-- Заголовок таблицы --}}
        <tr>
            <th rowspan="2" class="border p-2 w-6 text-center">
                {{ __('№') }}
            </th>

            <th rowspan="2" class="border p-2 w-14">
                {{ __('Вопрос') }}
            </th>

            <th rowspan="2" class="border p-2 w-14">
                {{ __('Ответ') }}
            </th>

            <th colspan="{{ count($reportData['classrooms']) }}" class="border p-2 text-center">
                {{ __('Класс') }}
            </th>

            <th rowspan="2" class="border p-2 text-center">
                {{ __('Итого') }}
            </th>
        </tr>

        {{-- Номера классов --}}
        <tr>
            @foreach ($reportData['classrooms'] as $cl)
                <th class="border p-2 text-center">
                    {{ $cl->grade }}{{ $cl->letter }}
                </th>
            @endforeach
        </tr>

        {{-- Основная часть таблицы --}}
        @foreach ($reportData['questions'] as $q)
            @foreach ($q['answers'] as $a)
                <tr>

                    {{-- Номер вопроса --}}
                    @if ($loop->first)
                        <td rowspan="{{ count($q['answers']) }}" class="border p-2 text-center">
                            {{ $q['number'] }}
                        </td>

                        <td rowspan="{{ count($q['answers']) }}" class="border p-2">
                            {{ $q['text'] }}
                        </td>
                    @endif

                    {{-- Вариант ответа --}}
                    <td class="border p-2">
                        {{ __($a['text']) }}
                    </td>

                    {{-- Значения по каждому классу --}}
                    @foreach ($a['marks'] as $m)
                        <td class="border p-2 text-center">
                            {{ $m }}
                        </td>
                    @endforeach

                    {{-- Итого по ответу --}}
                    <td class="border p-2 text-center">
                        {{ $a['total'] }}
                    </td>
                </tr>
            @endforeach
        @endforeach

    </table>
</div>
