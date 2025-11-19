<h3 class="mt-6 mb-2 font-semibold text-lg">
    {{ __('Свод по району') }} {{ $selectedDistrictTitle ?? '' }}
</h3>

<div class="overflow-x-auto max-w-full mt-2 border rounded-md">
    <table class="min-w-full border-collapse text-sm text-center whitespace-nowrap">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">{{ __('Организация') }}</th>
                <th class="border p-2">{{ __('Кол-во учащихся') }}</th>
                <th class="border p-2 text-green-700">{{ __('Прошли') }}</th>
                <th class="border p-2 text-red-700">{{ __('Не прошли') }}</th>
                <th class="border p-2 text-red-700">{{ __('Мальчик') }}</th>
                <th class="border p-2 text-red-700">{{ __('Девушка') }}</th>
                <th class="border p-2">% {{ __('прошедших') }}</th>
                @foreach (range(1, 11) as $grade)
                    <th class="border p-2">{{ $grade }} {{ __('Класс') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($schoolStats as $school)
                @include('components.survey.report.row-region-table', ['school' => $school])
            @endforeach
            @include('components.survey.report.table-footer')
        </tbody>
    </table>
</div>
