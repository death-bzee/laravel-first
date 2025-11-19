<div class="prose max-w-full">
    <table>
        <thead>
            <tr>
                <th>{{ __('№ п/п') }}</th>
                <th>{{ __('Социальные категории') }}</th>
                <th>{{ __('Кол-во') }}</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td>{{ __('Всего учащихся') }}:</td>
                <td>{{ $countStudents }}</td>
                <td></td>
            </tr>
            @if($countSocialStudents > 0)
                <tr>
                    <td></td>
                    <td>{{ __('По типу семьи') }}:</td>
                    <td>{{ $countSocialStudents }}</td>
                    <td></td>
                </tr>
            @endif
            @foreach($socialPassportData as $index => $entry)
                @if($entry instanceof \Illuminate\Support\Collection)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $index }}</strong></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach($entry as $level => $count)
                        <tr>
                            <td></td>
                            <td>{{ $level }}</td>
                            <td>{{ $count }}</td>
                            @if($countStudents > 0)
                                <td>{{ number_format(($count * 100) / $countStudents, 2) }}%</td>
                            @else
                                <td>0%</td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $index }}</td>
                        <td>{{ $entry }}</td>
                        @if($countStudents > 0)
                            <td>{{ number_format(($entry * 100) / $countStudents, 2) }}%</td>
                        @else
                            <td>0%</td>
                        @endif
                    </tr>
                @endif
            @endforeach

            {{-- Новый блок для уровней образования родителей --}}
            <tr>
                <td colspan="4"><strong>{{ __('Образование родителей или других законных представителей учащегося') }}</strong></td>
            </tr>
            @foreach($educationLevelData as $entry)
                <tr>
                    <td></td>
                    <td>{{ $entry['education_level'] }}</td>
                    <td>{{ $entry['students_count'] }}</td>
                    @if($countStudents > 0)
                        <td>{{ number_format(($entry['students_count'] * 100) / $countStudents, 2) }}%</td>
                    @else
                        <td>0%</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
