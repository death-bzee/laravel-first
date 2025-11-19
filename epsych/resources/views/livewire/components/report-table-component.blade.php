<div>
    @include('components.survey.report.filters')

    @if (!$surveySelectedId)
        <p class="text-gray-500 mt-4 italic text-center"></p>
    @else
        @if ($districtSelectedId == 0 && is_array($districtStats) && count($districtStats))
            @include('components.survey.report.district-table')
        @elseif ($districtSelectedId != 0 && is_array($schoolStats) && count($schoolStats))
            @include('components.survey.report.region-table')
        @else
            <p class="text-gray-500 mt-4">{{ __('Нет данных для отображения') }}</p>
        @endif
    @endif

    @if ($selectedSurveyReportTypeId == 3 && is_array($schoolStats) && count($schoolStats))
        @include('components.survey.report.methodic-table')
    @endif
</div>
