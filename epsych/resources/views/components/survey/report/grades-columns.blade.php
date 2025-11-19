@props(['item'])
@foreach (range(1, 11) as $grade)
    <td class="border p-2">
        {{ $item->{'class' . $grade . '_passed'} ?? 0 }} /
        {{ $item->{'class' . $grade . '_total'} ?? 0 }}
        ({{ $item->{'class' . $grade . '_percent'} ?? 0 }}%)
    </td>
@endforeach
