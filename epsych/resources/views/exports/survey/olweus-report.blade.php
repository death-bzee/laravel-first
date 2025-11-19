<table>
    <thead>
        <tr>
            <th>{{ __('Школа / Класс') }}</th>
            <th>{{ __('Кол-во учеников') }}</th>
            <th>{{ __('Кол-во прошедших') }}</th>
            <th colspan="3">{{ __('Тікелей белсенді буллинг') }}</th>
            <th colspan="3">{{ __('Жанама белсенді буллинг') }}</th>
            <th colspan="3">{{ __('Тікелей пассивті буллинг (виктимизация)') }}</th>
            <th colspan="3">{{ __('Жанама пассивті буллинг (виктимизация)') }}</th>
        </tr>
        <tr>
            @foreach (range(1, 4) as $i)
                <th>{{ __('Слабо выражен') }}</th>
                <th>{{ __('Умеренно') }}</th>
                <th>{{ __('Ярко выражен') }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach ($reportData as $name => $row)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ $row['total_students'] }}</td>
                <td>{{ $row['tested_students'] }}</td>

                @foreach (['direct_active', 'indirect_active', 'direct_passive', 'indirect_passive'] as $key)
                    <td>{{ $row[$key]['weak'] ?? 0 }}</td>
                    <td>{{ $row[$key]['medium'] ?? 0 }}</td>
                    <td>{{ $row[$key]['strong'] ?? 0 }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
