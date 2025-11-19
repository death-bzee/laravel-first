<form wire:submit="getSurveyReport" class="flex justify-content-between flex-wrap md:flex-nowrap gap-6 items-end">
    <div class="min-w-0 w-3/12">
        <x-label for="survey" value="{{ __('Отчет') }}" required />
        <x-select2 name="selectedSurveyReportTypeId" options="surveyReportTypes" watch />
        <x-input-error for="selectedSurveyReportTypeId" />
    </div>

    <div class="min-w-0 w-3/12">
        <x-label for="survey" value="{{ __('Методика') }}" required />
        <x-select2 name="surveySelectedId" options="surveys" watch />
        <x-input-error for="surveySelectedId" />
    </div>

    <div class="min-w-0 w-3/12">
        <x-label for="district" value="{{ __('Район') }}" required />
        <x-select2 name="districtSelectedId" options="districts" watch live />
        <x-input-error for="districtSelectedId" />
    </div>

    <div class="flex gap-3 justify-end pb-2 w-full md:w-3/12">
        <x-button class="flex-1 justify-center h-[42px]" target="getSurveyReport">
            {{ __('Показать') }}
        </x-button>

        @if ($this->hasData)
            <x-button type="button" wire:click="exportExcel"
                class="flex-1 justify-center h-[42px] !bg-green-600 !hover:bg-green-700 text-white">
                <span wire:loading.remove wire:target="exportExcel">
                    {{ __('Экспорт в Excel') }}
                </span>
                <span wire:loading wire:target="exportExcel">
                    {{ __('Загрузка...') }}
                </span>
            </x-button>
        @endif
    </div>
</form>
