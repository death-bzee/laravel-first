<tr>
    <td class="border p-2 text-left">{{ $school->organization_title }}</td>
    <td class="border p-2">{{ $school->total_students }}</td>
    <td class="border p-2 text-green-700">{{ $school->total_passed }}</td>
    <td class="border p-2 text-red-700">{{ $school->total_not_passed }}</td>
    <td class="border p-2">{{ $school->passed_percent }}%</td>
    <td class="border p-2 text-red-700">{{ $school->total_risk_students ?? 0 }}</td>

    @foreach (\App\Models\Survey\Survey::orderBy('id')->get() as $m)
        @php
            $id = $m->id;
            $classCount = $school->{'methodic_' . $id . '_classes'} ?? 0;
            $studentCount = $school->{'methodic_' . $id . '_students'} ?? 0;
            $riskCount = $school->{'methodic_' . $m['id'] . '_risk_students'} ?? 0;
        @endphp
        <td class="border p-2">{{ $classCount }}</td>
        <td class="border p-2">
            {{ $studentCount }}
            @if ($riskCount > 0)
                <div class="text-red-600 text-xs">{{ __('в риске') }}: {{ $riskCount }}</div>
            @endif
        </td>
    @endforeach
</tr>
