<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Survey\Survey;
use App\Models\Concerns\District;
use App\Services\Survey\Report\SurveyReportService;
use App\Exports\Survey\SurveyReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Enums\Survey\SurveyReportTypeEnum;

class ReportTableComponent extends Component
{
    public array $surveys = [];
    public array $districts = [];
    public array $districtStats = [];
    public array $schoolStats = [];
    public array $total = [];
    public array $surveyReportTypes = [];
    public array $methodics = [];

    public ?int $surveySelectedId = null;
    public int $districtSelectedId = 0;
    public ?string $selectedDistrictTitle = null;
    public int $selectedSurveyReportTypeId = 0;

    protected array $rules = [
        'surveySelectedId' => 'required|integer|min:1',
        'selectedSurveyReportTypeId' => 'required|integer|min:1',
    ];

    protected array $messages = [
        'surveySelectedId.required.min' => 'Пожалуйста, выберите методику.',
        'selectedSurveyReportTypeId.required.min' => 'Пожалуйста, выберите отчет.',
    ];

    protected SurveyReportService $reportService;

    public function boot(SurveyReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function mount()
    {
        $user = auth()->user(); // ✅ добавили

        $this->methodics = Survey::orderBy('id')->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'title' => is_array($m->title)
                    ? ($m->title['ru'] ?? reset($m->title))
                    : $m->title,
            ];
        })->toArray();

        // 🚫 Если районный пользователь без district_id — блокируем доступ
        if ($user->hasRole('correctional_service_region') && empty($user->region_id)) {
            abort(403, 'У вас не назначен район. Обратитесь к администратору.');
        }

        // 🚫 Если районный пользователь без district_id — блокируем доступ
        if ($user->hasRole('correctional_service_district') && empty($user->district_id)) {
            abort(403, 'У вас не назначена область. Обратитесь к администратору.');
        }

        // ✅ Разграничение прав
        if ($user->hasRole('correctional_service_region')) {
            // может видеть всю область и все районы
            $this->districts = [0 => __('Вся область')] +
                District::where('region_id', $user->region_id)
                ->orderBy('title')
                ->pluck('title', 'id')
                ->toArray();
        } elseif ($user->hasRole('correctional_service_district')) {
            // может видеть только свой район
            $this->districts = District::where('id', $user->district_id)
                ->pluck('title', 'id')
                ->toArray();

            $this->districtSelectedId = $user->district_id;
        } else {
            // ❌ нет роли — нет доступа
            abort(403, 'У вас нет прав на просмотр отчёта.');
        }

        $this->surveys = Survey::pluck('title', 'id')->toArray();
        $this->surveyReportTypes = SurveyReportTypeEnum::options();

        // ✅ инициализация totals
        $this->total = [
            'schools'    => 0,
            'students'   => 0,
            'passed'     => 0,
            'not_passed' => 0,
        ];
        foreach (range(1, 11) as $grade) {
            $this->total['class' . $grade] = 0;
        }
    }



    private function resetTotals(): void
    {
        $this->total = [
            'schools'    => 0,
            'students'   => 0,
            'passed'     => 0,
            'not_passed' => 0,
        ];

        foreach (range(1, 11) as $grade) {
            $this->total['class' . $grade] = 0;
        }
    }

    public function getSurveyReport()
    {

        $user = auth()->user();

        // 🚫 запрет без роли
        if (!$user->hasAnyRole(['correctional_service_region', 'correctional_service_district'])) {
            abort(403, 'Недостаточно прав для просмотра отчёта.');
        }

        // 🔒 районный пользователь всегда ограничен своим районом
        if ($user->hasRole('correctional_service_district')) {
            $this->districtSelectedId = $user->district_id;
        }

        // 🚫 если не региональная роль, но выбрана вся область (0)
        if (
            $this->districtSelectedId === 0 &&
            !$user->hasRole('correctional_service_region')
        ) {
            abort(403, 'Вы не можете просматривать данные по всей области.');
        }

        // ✅ проверяем тип отчёта
        if ($this->selectedSurveyReportTypeId == SurveyReportTypeEnum::METHODIC->value) {
            // если отчёт по методикам — проверяем только тип отчёта
            $this->validate([
                'selectedSurveyReportTypeId' => 'required|integer|min:1',
            ]);
        } else {
            // для всех остальных — стандартная валидация
            $this->validate();
        }

        $this->resetTotals();
        $this->selectedDistrictTitle = null;
        $this->districtStats = [];
        $this->schoolStats   = [];

        if ($this->selectedSurveyReportTypeId == SurveyReportTypeEnum::REGION->value) {
            if ($this->districtSelectedId === 0) {
                $this->loadRegionSummary();
            } else {
                $this->loadDistrictSummary();
            }
        } elseif ($this->selectedSurveyReportTypeId == SurveyReportTypeEnum::DISTRICT->value) {
            $this->loadDistrictSummary();
        } elseif ($this->selectedSurveyReportTypeId == SurveyReportTypeEnum::METHODIC->value) {
            $this->loadMethodicsSummary();
        }

        session()->flash('success', __('Фильтры применены.'));
    }


    private function loadRegionSummary(): void
    {
        $regionId = auth()->user()->region_id;
        $data = $this->reportService->getRegionSummary($regionId);

        $this->districtStats = $data['rows']->toArray();
        $this->total = $data['totals'];
    }

    private function loadDistrictSummary(): void
    {
        $districtId = $this->districtSelectedId;
        $district = District::find($districtId);
        $this->selectedDistrictTitle = $district ? ($district->title['ru'] ?? $district->title) : null;

        $data = $this->reportService->getDistrictSummary($districtId);

        $this->schoolStats = $data['rows']->toArray();
        $this->total = $data['totals'];
    }

    private function loadMethodicsSummary(): void
    {
        $regionId = auth()->user()->region_id;
        $districtId = $this->districtSelectedId ?: null;

        $data = $this->reportService->getMethodicsSummary($regionId, $districtId);

        $this->schoolStats = $data['rows']->toArray();
        $this->total = $data['totals'];
    }


    public function exportExcel()
    {
        $type = $this->selectedSurveyReportTypeId;

        $data = match ($type) {
            1 => $this->districtStats,
            2, 3 => $this->schoolStats,
            default => [],
        };

        if (empty($data)) {
            session()->flash('error', 'Нет данных для экспорта.');
            return;
        }

        $fileName = match ($type) {
            1 => 'Отчет_по_районам.xlsx',
            2 => 'Отчет_по_школам.xlsx',
            3 => 'Отчет_по_методикам.xlsx',
            default => 'Отчет.xlsx',
        };

        return Excel::download(
            new SurveyReportExport(
                SurveyReportTypeEnum::from($type),
                $data,
                $this->methodics ?: session('methodics_export', []),
                $this->total ?? [],
                $this->selectedDistrictTitle ?? null
            ),
            $fileName
        );
    }

    public function getHasDataProperty(): bool
    {
        return match ($this->selectedSurveyReportTypeId) {
            1 => !empty($this->districtStats),
            2, 3 => !empty($this->schoolStats),
            default => false,
        };
    }

    protected function rules(): array
    {
        // 🔹 Региональная роль — вся область и все районы
        if ($this->isRegionRole()) {
            return [
                'districtSelectedId' => [
                    'sometimes',
                    'integer',
                    'in:' . implode(',', array_keys($this->districts)),
                ],
                'surveySelectedId' => [
                    'required',
                    'integer',
                    'in:' . implode(',', array_keys($this->surveys)),
                ],
            ];
        }

        // 🔹 Районная роль — только свой район
        if ($this->isDistrictRole()) {
            $districtId = auth()->user()->district_id;

            return [
                'districtSelectedId' => [
                    'required',
                    'integer',
                    'in:' . $districtId,
                ],
                'surveySelectedId' => [
                    'required',
                    'integer',
                    'in:' . implode(',', array_keys($this->surveys)),
                ],
            ];
        }

        // fallback
        return [
            'surveySelectedId' => 'required|integer|min:1',
            'selectedSurveyReportTypeId' => 'required|integer|min:1',
        ];
    }

    private function isRegionRole(): bool
    {
        return auth()->user()?->hasRole('correctional_service_region');
    }

    private function isDistrictRole(): bool
    {
        return auth()->user()?->hasRole('correctional_service_district');
    }



    public function render()
    {
        return view('livewire.components.report-table-component');
    }
}
