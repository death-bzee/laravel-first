<div class="overflow-x-auto max-w-full mt-4 border rounded-md">
    <table class="min-w-full border-collapse text-sm text-center whitespace-nowrap">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">{{ __('Район') }}</th>
                <th class="border p-2">{{ __('Кол-во школ') }}</th>
                <th class="border p-2">{{ __('Кол-во учащихся') }}</th>
                <th class="border p-2 text-green-700">{{ __('Прошли') }}</th>
                <th class="border p-2 text-red-700">{{ __('Не прошли') }}</th>
                <th class="border p-2">% {{ __('прошедших') }}</th>
                @foreach (range(1, 11) as $grade)
                    <th class="border p-2">{{ $grade }} {{ __('Класс') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($districtStats as $district)
                @include('components.survey.report.row-district-table', ['district' => $district])
            @endforeach
            @include('components.survey.report.table-footer')
        </tbody>
    </table>
</div>
