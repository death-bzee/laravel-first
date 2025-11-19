<tr>
    <td class="border p-2 font-medium">{{ $district->district_title ?? '—' }}</td>
    <td class="border p-2">{{ $district->schools_count ?? 0 }}</td>
    <td class="border p-2">{{ $district->total_students ?? 0 }}</td>
    <td class="border p-2 text-green-700">{{ $district->passed_count ?? 0 }}</td>
    <td class="border p-2 text-red-700">{{ $district->not_passed_count ?? 0 }}</td>
    <td class="border p-2 font-semibold">{{ $district->passed_percent ?? 0 }}%</td>
    @foreach (range(1, 11) as $grade)
        @include('components.survey.report.grades-columns', ['item' => $district])
    @endforeach
</tr>
