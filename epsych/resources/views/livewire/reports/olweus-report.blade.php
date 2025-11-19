          <div class="mt-8 overflow-x-auto">
              <h2 class="font-bold text-lg mb-4 text-center">
                  @if ($this->isDistrictRole())
                      {{ __('Д. Олвеус — сводные данные по школам района') }}
                  @elseif ($this->isPsychologistRole())
                      @if ($classroom_selected_id > 0)
                          {{ __('Д. Олвеус — сводные данные по классу') }}
                      @else
                          {{ __('Д. Олвеус — сводные данные по классам школы') }}
                      @endif
                  @elseif ($this->isRegionRole())
                      {{ __('Д. Олвеус — сводные данные по районам области') }}
                  @endif
              </h2>



              <table class="min-w-full border border-gray-300 text-center text-sm">
                  <thead class="bg-gray-100 font-semibold text-center">
                      <tr>
                          <th rowspan="2" class="border px-3 py-2 align-middle">{{ __('Школа / Класс') }}</th>
                          <th rowspan="2" class="border px-3 py-2 align-middle">{{ __('Количество учеников') }}
                          </th>
                          <th rowspan="2" class="border px-3 py-2 align-middle">{{ __('Количество прошедших') }}
                          </th>

                          <th colspan="3" class="border px-3 py-2">{{ __('Прямой активный буллинг') }}</th>
                          <th colspan="3" class="border px-3 py-2">{{ __('Косвенный активный буллинг') }}</th>
                          <th colspan="3" class="border px-3 py-2">
                              {{ __('Прямой пассивный буллинг (виктимизация)') }}</th>
                          <th colspan="3" class="border px-3 py-2">
                              {{ __('Косвенный пассивный буллинг (виктимизация)') }}</th>
                      </tr>

                      <tr>
                          @foreach (range(1, 4) as $i)
                              <th class="border px-2 py-1">{{ __('Слабо выражен') }}</th>
                              <th class="border px-2 py-1">{{ __('Умеренно выражен') }}</th>
                              <th class="border px-2 py-1">{{ __('Ярко выражен') }}</th>
                          @endforeach
                      </tr>
                  </thead>


                  <tbody>
                      @foreach ($classesSummary as $class)
                          @php
                              $classId = $class['classroom_id'];
                              $className = $class['classroom_name'];
                              $row = $reportData[$classId] ?? null;
                          @endphp

                          <tr>
                              <td class="border px-2 py-1 text-left">{{ $className }}</td>
                              <td class="border px-2 py-1">{{ $class['students_count'] }}</td>

                              {{-- прошедшие --}}
                              <td class="border px-2 py-1">
                                  {{ $testedByClass[$className] ?? 0 }}
                              </td>

                              {{-- шкалы --}}
                              @foreach (['direct_active', 'indirect_active', 'direct_passive', 'indirect_passive'] as $scale)
                                  <td class="border px-2 py-1">{{ $row[$scale]['weak'] ?? 0 }}</td>
                                  <td class="border px-2 py-1">{{ $row[$scale]['medium'] ?? 0 }}</td>
                                  <td class="border px-2 py-1">{{ $row[$scale]['strong'] ?? 0 }}</td>
                              @endforeach
                          </tr>
                      @endforeach
                  </tbody>
                  <tfoot class="bg-gray-50 font-bold">
                      <tr>
                          <td class="border px-2 py-1 text-right">{{ __('Итого:') }}</td>
                          <td class="border px-2 py-1">{{ $schoolTotalStudents ?? '' }}</td>
                          <td class="border px-2 py-1">{{ array_sum($testedByClass) }}</td>

                          @foreach (['direct_active', 'indirect_active', 'direct_passive', 'indirect_passive'] as $scale)
                              <td class="border px-2 py-1">{{ $totals[$scale]['weak'] }}</td>
                              <td class="border px-2 py-1">{{ $totals[$scale]['medium'] }}</td>
                              <td class="border px-2 py-1">{{ $totals[$scale]['strong'] }}</td>
                          @endforeach
                      </tr>
                  </tfoot>

              </table>

          </div>
