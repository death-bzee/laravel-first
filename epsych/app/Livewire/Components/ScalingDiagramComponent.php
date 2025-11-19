<?php

namespace App\Livewire\Components;

use App\Data\Survey\Scaling\ScalingAggregationContextData;
use App\Models\Organization;
use App\Models\Survey\Survey;
use App\Services\Student\ClassroomService;
use App\Services\Survey\Chart\AnxietyChartDataService;
use App\Services\Survey\Chart\GroupAnxietyChartDataService;
use App\Services\Survey\Scaling\SurveyScalingDataService;
use App\Services\User\ResponsibilityAreaService;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Survey\OlweusReportExport;
use App\Services\Survey\Report\SurveyReportManager;

class ScalingDiagramComponent extends Component
{
    public array $classrooms = [];

    public array $districts = [];

    public array $organizations = [];

    public array $surveys = [];

    public int $classroom_selected_id = 0;

    public int $district_selected_id = 0;

    public int $organization_selected_id = 0;

    public ?int $survey_selected_id = null;

    public ?Collection $scalingData = null;

    public ?array $generalAnxietyFloatingChartData = null;

    public ?array $riskAnxietyFloatingChartData = null;

    public ?array $anxietyChartData = null;

    public bool $submitted = false;
    public array $totals = [];

    public ?string $reportExportClass = null;
    public ?string $reportTitle = null;
    public ?string $reportView = null;

    // public ?array $bullyingStats = null;
    public ?array $reportData = null;

    public array $classesSummary = [];
    public array $testedByClass = [];
    public ?int $schoolTotalStudents = null;

    public function mount(): void
    {
        $this->loadSurveys();
        $this->loadResponsibilityData();
    }

    public function getScalingDiagram(): void
    {
        set_time_limit(0);
        $this->submitted = true;
        $this->validate();

        $organizationId = $this->organization_selected_id;

        if ($this->isPsychologistRole()) {
            $organizationId = auth()->user()->organization_id;
        }

        $this->scalingData = app(SurveyScalingDataService::class)
            ->aggregateByUserContext(new ScalingAggregationContextData(
                surveyId: $this->survey_selected_id,
                districtId: $this->district_selected_id,
                organizationId: $organizationId,
                classroomId: $this->classroom_selected_id,
            ));

        $this->generalAnxietyFloatingChartData = app(GroupAnxietyChartDataService::class)
            ->prepareFloatingChartData($this->scalingData, 'isGeneral');

        $this->riskAnxietyFloatingChartData = app(GroupAnxietyChartDataService::class)
            ->prepareFloatingChartData($this->scalingData, 'isRisk');

        $this->anxietyChartData = app(AnxietyChartDataService::class)
            ->prepareAnxietyChartData($this->scalingData);

        if ($this->survey_selected_id == 38 || $this->survey_selected_id == 37 || $this->survey_selected_id == 36) {
            // 🔹 НОВОЕ: модульная система отчётов
            $survey = Survey::find($this->survey_selected_id);

            /** @var SurveyReportManager $reportManager */
            $reportManager = app(SurveyReportManager::class);

            $reportService = $reportManager->forSurvey($survey);

            if ($reportService) {

                $role = null;
                if ($this->isRegionRole()) {
                    $role = 'region';
                } elseif ($this->isDistrictRole()) {
                    $role = 'district';
                } elseif ($this->isPsychologistRole()) {
                    $role = 'psychologist';
                }


                $report = $reportService->build($this->scalingData, [
                    'district_id'     => $this->district_selected_id,
                    'organization_id' => $organizationId,
                    'classroom_id'    => $this->classroom_selected_id,
                    'survey_id'       => $this->survey_selected_id,
                    'role'            => $role,
                    'user'            => auth()->user(),
                ]);

                $this->reportData           = $report->reportData;
                $this->classesSummary       = $report->classesSummary;
                $this->testedByClass        = $report->testedByClass;
                $this->schoolTotalStudents  = $report->schoolTotalStudents;
                $this->totals               = $report->totals;
                $this->reportExportClass    = $report->exportClass;
                $this->reportTitle          = $report->exportTitle;
                $this->reportView = $report->bladeView;
            } else {
                // если для методики нет отчёта — очищаем
                $this->reportData = null;
                $this->classesSummary = [];
                $this->testedByClass = [];
                $this->schoolTotalStudents = null;
                $this->totals = [];
                $this->reportExportClass = null;
                $this->reportTitle = null;
                $this->reportView = null;
            }
        }
    }

    public function exportReport()
    {
        if (empty($this->reportData) || ! $this->reportExportClass) {
            $this->dispatch('notify', title: 'Нет данных для экспорта', type: 'warning');
            return;
        }

        $fileName = ($this->reportTitle ?: 'Отчёт') . '_' . now()->format('Y-m-d_His') . '.xlsx';

        $exportClass = $this->reportExportClass;
        // -----------------------------
        // 🔥 УНИВЕРСАЛЬНЫЙ ВЫЗОВ ЭКСПОРТА
        // -----------------------------

        // узнай, сколько параметров требует экспорт-класс
        $reflection = new \ReflectionClass($exportClass);
        $constructor = $reflection->getConstructor();
        $params = $constructor ? $constructor->getParameters() : [];

        // формируем массив аргументов динамически
        $args = [];

        foreach ($params as $param) {
            $name = $param->getName();

            // поддержка любых отчётов
            if ($name === 'classesSummary') {
                $args[] = $this->classesSummary ?? [];
            } elseif ($name === 'testedByClass') {
                $args[] = $this->testedByClass ?? [];
            } elseif ($name === 'reportData' || $name === 'report') {
                $args[] = $this->reportData ?? [];
            } elseif ($name === 'title') {
                $args[] = $this->reportTitle ?? 'Отчёт';
            } else {
                // подстраховка — если появятся новые отчёты
                $args[] = null;
            }
        }

        return Excel::download(
            $reflection->newInstanceArgs($args),
            $fileName
        );
    }


    protected function rules(): array
    {
        if ($this->isRegionRole()) {
            return [
                'district_selected_id' => ['sometimes', 'integer', 'in:' . implode(',', array_keys($this->districts))],
                'survey_selected_id' => ['required', 'integer', 'in:' . implode(',', array_keys($this->surveys))],
            ];
        }

        if ($this->isDistrictRole()) {
            return [
                'organization_selected_id' => ['sometimes', 'integer', 'in:' . implode(',', array_keys($this->organizations))],
                'survey_selected_id' => ['required', 'integer', 'in:' . implode(',', array_keys($this->surveys))],
            ];
        }

        if ($this->isPsychologistRole()) {
            return [
                'classroom_selected_id' => ['sometimes', 'integer', 'in:' . implode(',', array_keys($this->classrooms))],
                'survey_selected_id' => ['required', 'integer', 'in:' . implode(',', array_keys($this->surveys))],
            ];
        }

        return [];
    }

    public function render(): View
    {
        return view('livewire.components.scaling-diagram-component', [
            'riskAnxietyFloatingChartData' => $this->riskAnxietyFloatingChartData,
            'generalAnxietyFloatingChartData' => $this->generalAnxietyFloatingChartData,
            'anxietyChartData' => $this->anxietyChartData,
            // ✔ выводим в blade
            'schoolTotalStudents' => $this->schoolTotalStudents,
            // ✔ список классов
            'classesSummary' => $this->classesSummary,
            'testedByClass' => $this->testedByClass,
            'totals' => $this->totals
        ]);
    }

    private function loadSurveys(): void
    {
        $this->surveys = Survey::query()
            ->pluck('title', 'id')
            ->toArray();
    }

    private function loadResponsibilityData(): void
    {
        if ($this->isRegionRole()) {
            $this->districts = [0 => __('Вся область')] + app(ResponsibilityAreaService::class)->getAccessibleDistricts();
        }

        if ($this->isDistrictRole()) {
            $this->organizations = [0 => __('Весь район')] + app(ResponsibilityAreaService::class)->getAccessibleOrganizations();
        }

        if ($this->isPsychologistRole()) {
            $this->classrooms = [0 => __('Вся школа')] + app(ClassroomService::class)->getAccessibleClassrooms();
        }
    }

    private function isRegionRole(): bool
    {
        return auth()->user()?->hasRole('correctional_service_region');
    }

    private function isDistrictRole(): bool
    {
        return auth()->user()?->hasRole('correctional_service_district');
    }

    private function isPsychologistRole(): bool
    {
        return auth()->user()?->hasAnyRole(['psychologist', 'social_pedagogue']);
    }
}
