<tr>
    <td class="border p-2 text-left">{{ $school->organization_title ?? '—' }}</td>
    <td class="border p-2">{{ $school->total_students ?? 0 }}</td>
    <td class="border p-2 text-green-700">{{ $school->passed_count ?? 0 }}</td>
    <td class="border p-2 text-red-700">{{ $school->not_passed_count ?? 0 }}</td>
    <td class="border p-2 text-red-700">{{ $school->male_count ?? 0 }}</td>
    <td class="border p-2 text-red-700">{{ $school->female_count ?? 0 }}</td>
    <td class="border p-2 font-semibold">{{ $school->passed_percent ?? 0 }}%</td>
    @foreach (range(1, 11) as $grade)
        @include('components.survey.report.grades-columns', ['item' => $school])
    @endforeach
</tr>
