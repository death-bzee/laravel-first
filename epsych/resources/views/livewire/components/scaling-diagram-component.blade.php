<div>
    <form wire:submit="getScalingDiagram" class="grid grid-cols-1 md:grid-cols-[1fr_1fr_150px] gap-6 items-end">

        <div class="min-w-0">
            <x-label for="survey" value="{{ __('Методика') }}" required />
            <x-select2 name="survey_selected_id" options="surveys" watch />
            <x-input-error for="survey_selected_id" />
        </div>

        @hasanyrole('correctional_service_region')
            <div class="min-w-0">
                <x-label for="district" value="{{ __('Район') }}" required />
                <x-select2 name="district_selected_id" options="districts" watch live />
                <x-input-error for="district_selected_id" />
            </div>
        @endhasanyrole

        @hasanyrole('correctional_service_district')
            <div class="min-w-0">
                <x-label for="organization" value="{{ __('Школа') }}" required />
                <x-select2 name="organization_selected_id" options="organizations" watch live />
                <x-input-error for="organization_selected_id" />
            </div>
        @endhasanyrole

        @hasanyrole('psychologist|social_pedagogue')
            <div class="min-w-0">
                <x-label for="classroom" value="{{ __('Класс') }}" required />
                <x-select2 name="classroom_selected_id" options="classrooms" watch live />
                <x-input-error for="classroom_selected_id" />
            </div>
        @endhasanyrole

        <div class="flex justify-end pb-2">
            <x-button class="text-nowrap w-full justify-center" target="getScalingDiagram">
                {{ __('Показать') }}
            </x-button>
        </div>

    </form>

    @if ($submitted && $reportData)
        <div class="flex justify-end mb-4">
            <button wire:click="exportReport" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                {{ __('Экспорт в Excel') }}
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 mt-8">
        @if ($anxietyChartData)
            <div>
                <livewire:components.survey.chart.anxiety-chart-component :chartData="$anxietyChartData" :key="'anxiety-bar-' . $survey_selected_id"
                    typeDiagram="bar" />
            </div>
            <div>
                <livewire:components.survey.chart.anxiety-chart-component :chartData="$anxietyChartData" :key="'anxiety-pie-' . $survey_selected_id"
                    typeDiagram="pie" />
            </div>
        @endif
        @if ($riskAnxietyFloatingChartData)
            <div>
                <div class="mb-4 text-center font-bold">{{ __('Ученики под группой риска') }}</div>
                <livewire:components.survey.chart.anxiety-floating-chart-component :chartData="$riskAnxietyFloatingChartData"
                    :key="'floating-anxiety-' . $survey_selected_id" />
            </div>
        @endif

        @if ($generalAnxietyFloatingChartData)
            <div>
                <div class="mb-4 text-center font-bold">{{ __('Общие данные по каждому ученику') }}</div>
                <livewire:components.survey.chart.anxiety-floating-chart-component :chartData="$generalAnxietyFloatingChartData"
                    :key="'floating-anxiety-' . $survey_selected_id" />
            </div>
        @endif

        @if ($submitted && !$anxietyChartData && !$riskAnxietyFloatingChartData && !$generalAnxietyFloatingChartData)
            <div class="text-center text-gray-500 text-sm">
                {{ __('Статистические данные по выбранным критериям отсутствуют.') }}'
            </div>
        @endif

        @if ($submitted && $reportData && count($reportData))
            @includeIf('livewire.reports.' . $reportView)
        @endif
    </div>

</div>

@push('scripts')
    @vite(['resources/js/chart/index.js'])
@endpush
