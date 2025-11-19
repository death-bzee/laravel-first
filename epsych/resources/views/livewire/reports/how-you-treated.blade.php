@if ($reportTitle)
    <h2 class="text-xl font-bold mb-4 text-center">
        {{ __($reportTitle) }}
    </h2>
@endif

<div class="overflow-x-auto">
    <table class="min-w-full border text-xs">

        {{-- Заголовки --}}
        <tr>
            <th colspan="3" class="border p-2 text-center">
                {{ __('Кол учеников') }}
            </th>

            <th colspan="{{ count($reportData['students']) }}" class="border p-2 text-center">
                {{ __('Код / номер ребёнка') }}
            </th>

            <th rowspan="2" class="border p-2 text-center">
                {{ __('Итого') }}
            </th>
        </tr>

        <tr>
            <th class="border p-2 text-center w-8">{{ __('№') }}</th>
            <th class="border p-2 w-8">{{ __('Вопрос') }}</th>
            <th class="border p-2 w-8">{{ __('Ответ') }}</th>

            @foreach ($reportData['students'] as $i => $s)
                <th class="border p-1 text-center">{{ $i + 1 }}</th>
            @endforeach
        </tr>

        {{-- Контент --}}
        @foreach ($reportData['questions'] as $q)
            @foreach ($q['answers'] as $a)
                <tr>
                    @if ($loop->first)
                        <td rowspan="{{ count($q['answers']) }}" class="border p-2 text-center align-top w-8">
                            {{ $q['number'] }}
                        </td>

                        <td rowspan="{{ count($q['answers']) }}" class="border p-2 align-top w-8">
                            {{ __($q['text']) }}
                        </td>
                    @endif

                    <td class="border p-2 w-8">{{ __($a['text']) }}</td>

                    {{-- Отметки --}}
                    @foreach ($a['marks'] as $mark)
                        <td class="border p-1 text-center">{{ $mark }}</td>
                    @endforeach

                    {{-- Итог по варианту --}}
                    <td class="border p-2 text-center font-bold">{{ $a['total'] }}</td>
                </tr>
            @endforeach
        @endforeach

        {{-- Итоговая строка --}}
        <tr class="bg-gray-100 font-bold">
            <td class="border p-2 text-center" colspan="3">{{ __('Итого') }}</td>

            @foreach ($reportData['students'] as $i => $s)
                <td class="border p-2 text-center">
                    {{ $reportData['totals_per_student'][$i] ?? 0 }}
                </td>
            @endforeach

            <td class="border p-2 text-center">
                {{ $reportData['total_all'] ?? 0 }}
            </td>
        </tr>

    </table>
</div>
