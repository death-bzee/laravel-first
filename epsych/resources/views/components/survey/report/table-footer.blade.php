<tr class="bg-gray-50 font-bold">
    <td class="border p-2 text-right">{{ __('Всего') }}:</td>
    <td class="border p-2">{{ $total['students'] ?? ($total['schools'] ?? 0) }}</td>
    <td class="border p-2 text-green-700">{{ $total['passed'] ?? 0 }}</td>
    <td class="border p-2 text-red-700">{{ $total['not_passed'] ?? 0 }}</td>
    <td class="border p-2"></td>
    @foreach (range(1, 11) as $grade)
        <td class="border p-2">{{ $total['class' . $grade] ?? 0 }}</td>
    @endforeach
</tr>
