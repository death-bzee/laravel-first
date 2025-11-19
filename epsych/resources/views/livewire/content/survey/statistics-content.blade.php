<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>{{ __('Статистика тестирования') }}</h2>
            @if(!empty($studentData))
                <div class="mt-2">
                    <p>
                        {{ $studentData['surname'] }}
                        {{ $studentData['name'] }}
                        {{ $studentData['patronymic'] }}
                    </p>
                    <p>{{ __('Класс') }}: {{ $studentData['grade'] }}{{ $studentData['letter'] }}</p>
                </div>
            @endif
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ __('Общие показатели') }}</h4>
                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Всего ответов') }}
                            <span class="badge bg-primary rounded-pill">{{ $statistics['total_answers'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Статус') }}
                            <span class="badge {{ $statistics['completed'] ? 'bg-success' : 'bg-warning' }} rounded-pill">
                                {{ $statistics['completed'] ? __('Завершено') : __('В процессе') }}
                            </span>
                        </li>
                    </ul>
                </div>

                @if(!empty($statistics['scaling']))
                    <div class="col-md-6">
                        <h4>{{ __('Шкалы') }}</h4>
                        <ul class="list-group">
                            @foreach($statistics['scaling'] as $scale)
                                <li class="list-group-item">
                                    <strong>{{ $scale['scaleName'] }}:</strong>
                                    {{ $scale['levelName'] }} ({{ $scale['score'] }})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div>
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b border-gray-200 p-4">
                        @if(!empty($studentData))
                            <div class="flex flex-col space-y-1">
                                <p class="text-gray-600">{{ __('Класс') }}: {{ $studentData['grade'] }}{{ $studentData['letter'] }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 space-y-6">
                        @if(!empty($statistics['surveys']))
                            @foreach($statistics['surveys'] as $survey)
                                <div class="bg-white rounded-lg border border-gray-200">
                                    <div class="border-b border-gray-200 p-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $survey['title'] }}</h3>
                                    </div>

                                    <div class="p-4">
                                        <div class="grid grid-cols-1 gap-6">
                                            <div class="bg-gray-50 rounded-lg p-4">
                                                <div class="flex flex-wrap gap-4">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm text-gray-600">{{ __('Всего назначено:') }}</span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                            {{ $survey['total_assigned'] }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm text-gray-600">{{ __('Завершено:') }}</span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            {{ $survey['completed_count'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="overflow-x-auto">
                                                <table class="min-w-full table-auto">
                                                    <thead>
                                                        <tr class="bg-gray-50">
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                {{ __('Ученик') }}
                                                            </th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                {{ __('Статус') }}
                                                            </th>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                {{ __('Результаты') }}
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-200">
                                                        @foreach($survey['students'] as $student)
                                                            <tr>
                                                                <td class="px-3 py-2 text-sm">{{ $student['name'] }}</td>
                                                                <td class="px-3 py-2">
                                                                    <span @class([
                                                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                                        'bg-green-100 text-green-800' => $student['status'] === 'completed',
                                                                        'bg-yellow-100 text-yellow-800' => $student['status'] !== 'completed',
                                                                    ])>
                                                                        {{ $student['status'] === 'completed' ? __('Завершен') : __('В процессе') }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-3 py-2 text-sm">
                                                                    @if(!empty($student['diagnosis']))
                                                                        <div class="space-y-1">
                                                                            @foreach($student['diagnosis'] as $scale)
                                                                                <div>
                                                                                    <span class="font-medium">{{ $scale['scaleName'] }}:</span>
                                                                                    {{ $scale['levelName'] }}
                                                                                    <span class="text-gray-500">({{ $scale['score'] }})</span>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="text-gray-400">{{ __('Нет данных') }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="rounded-lg bg-blue-50 p-4">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">{{ __('Нет данных для отображения') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
