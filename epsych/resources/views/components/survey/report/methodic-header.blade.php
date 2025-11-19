<tr>
    <th rowspan="2" class="border p-2">{{ __('Школа') }}</th>
    <th rowspan="2" class="border p-2">{{ __('Количество учащихся') }}</th>
    <th rowspan="2" class="border p-2 text-green-700">{{ __('Прошли') }}</th>
    <th rowspan="2" class="border p-2 text-red-700">{{ __('Не прошли') }}</th>
    <th rowspan="2" class="border p-2">% {{ __('прошедших') }}</th>
    <th rowspan="2" class="border p-2 text-red-700">{{ __('В группе риска') }}</th>
    @foreach (\App\Models\Survey\Survey::orderBy('id')->get() as $m)
        <th colspan="2" class="border p-2 font-semibold">
            {{ is_array($m->title) ? $m->title['ru'] ?? reset($m->title) : $m->title }}
        </th>
    @endforeach
</tr>
<tr>
    @foreach (\App\Models\Survey\Survey::orderBy('id')->get() as $m)
        <th class="border p-2">{{ __('Кол-во классов') }}</th>
        <th class="border p-2">{{ __('Кол-во детей') }}</th>
    @endforeach
</tr>
