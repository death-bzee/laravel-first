<?php

namespace App\Services\Survey\Report;

use App\Models\Survey\Survey;
use App\Repositories\Organization\OrganizationRepository;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Survey\SurveyResultRepository;
use App\Repositories\Survey\SurveyReportRepository;
use Illuminate\Support\Collection;


readonly class SurveyReportService
{
    public function __construct(
        private SurveyResultRepository $surveyResultRepository,
        private StudentRepository $studentRepository,
        private OrganizationRepository $organizationRepository,
        private SurveyReportRepository $reportRepository
    ) {}

    public function getOrganizationSurveyReport(int $organizationId, int $surveyId): Collection
    {
        // 1. Результаты по вопросам/ответам
        $results = $this->surveyResultRepository
            ->getResultsByOrganizationsAndSurvey($organizationId, $surveyId);

        $report = $results->groupBy([
            fn($result) => $result->question->title,
            fn($result) => $result->option?->title ?? __('Без ответа'),
        ])->map(function (Collection $optionsByQuestion) {
            return $optionsByQuestion->map(fn(Collection $resultsByOption) => $resultsByOption->count());
        });

        // 2. Количество студентов организации
        $totalStudents = $this->studentRepository->getStudentCount($organizationId);

        // 3. Количество 5–11 классы
        $studentsInGrades = $this->studentRepository->getStudentCountInGrades($organizationId);

        // 4. Уникальные студенты, реально прошедшие тест
        $testedStudentIds = $results
            ->pluck('surveyAssignment.student')
            ->filter()
            ->unique('id');

        $testedStudents = $testedStudentIds->count();

        // 5. Разделение по полу среди прошедших survey
        $studentsByGender = $testedStudentIds
            ->groupBy('gender')
            ->map(fn($group) => $group->count());

        $surveyTitle = Survey::where('id', $surveyId)->pluck('title')->first();

        return collect([
            'survey_title' => $surveyTitle,
            'stats' => [
                'total_students' => $totalStudents,
                'students_in_grades' => $studentsInGrades,
                'tested_students' => $testedStudents,
                'students_by_gender' => $studentsByGender,
            ],
            'report' => $report,
        ]);
    }

    public function getRegionSurveyReport(int $regionId, int $surveyId): Collection
    {
        // 1. Получаем организации региона через репозиторий
        $organizations = $this->organizationRepository->getByRegion($regionId);

        if ($organizations->isEmpty()) {
            return collect();
        }

        // 2. ID всех организаций
        $organizationIds = $organizations->pluck('id');

        // 3. Получаем результаты по всем организациям региона
        $results = $this->surveyResultRepository
            ->getResultsByOrganizationsAndSurvey($organizationIds, $surveyId);

        // 4. Все уникальные студенты, прошедшие тест
        $testedStudents = $results
            ->pluck('surveyAssignment.student')
            ->filter()
            ->unique('id');

        // 5. Группировка по районам
        $districtReports = $organizations
            ->groupBy('district_id')
            ->map(function (Collection $districtOrganizations, int $districtId) use ($results, $testedStudents) {
                $orgIds = $districtOrganizations->pluck('id');

                // Отфильтровываем результаты только по этим организациям
                $districtResults = $results->filter(
                    fn($result) => $orgIds->contains($result->surveyAssignment->student?->organization_id)
                );

                // Формируем агрегированный отчёт по вопросам и вариантам ответов
                $report = $districtResults
                    ->groupBy(fn($r) => $r->question->title)
                    ->map(
                        fn(Collection $resultsByQuestion) => $resultsByQuestion
                            ->groupBy(fn($r) => $r->option?->title ?? __('Без ответа'))
                            ->map(
                                fn(Collection $answers) => $answers
                                    ->pluck('surveyAssignment.student_id')
                                    ->unique()
                                    ->count()
                            )
                    );

                // Сколько всего студентов в школах района
                $totalStudents = $this->studentRepository->getStudentCountByOrganizations($orgIds);

                // Сколько реально прошли тест
                $testedCount = $testedStudents
                    ->filter(fn($student) => $orgIds->contains($student->organization_id))
                    ->count();

                // Не прошли тест
                $notTestedCount = max($totalStudents - $testedCount, 0);

                // Половой состав (среди прошедших)
                $studentsByGender = $testedStudents
                    ->filter(fn($student) => $orgIds->contains($student->organization_id))
                    ->groupBy('gender')
                    ->map(fn($group) => $group->count());

                $firstOrganization = $districtOrganizations->first();

                return [
                    'district_id' => $districtId,
                    'district_title' => $firstOrganization?->district?->title,
                    'stats' => [
                        'schools' => $districtOrganizations->count(),
                        'total_students' => $totalStudents,
                        'tested_students' => $testedCount,
                        'not_tested_students' => $notTestedCount,
                        'students_by_gender' => $studentsByGender,
                    ],
                    'report' => $report,
                ];
            });

        // 6. Название методики
        $surveyTitle = Survey::query()
            ->where('id', $surveyId)
            ->value('title');

        // 7. Финальный ответ
        return collect([
            'survey_title' => $surveyTitle,
            'region_id' => $regionId,
            'districts' => $districtReports->values(),
        ]);
    }


    /**
     * 📊 Отчёт по области
     */
    public function getRegionSummary(int $regionId): array
    {
        $districts = $this->reportRepository->getRegionSummary($regionId);

        // Добавляем проценты и итоги
        $districts = $districts->map(function ($d) {
            $d->passed_percent = $d->total_students > 0
                ? round(($d->passed_count / $d->total_students) * 100, 1)
                : 0;

            foreach (range(1, 11) as $grade) {
                $total = $d->{'class' . $grade . '_total'} ?? 0;
                $passed = $d->{'class' . $grade . '_passed'} ?? 0;
                $d->{'class' . $grade . '_percent'} = $total > 0
                    ? round(($passed / $total) * 100, 1)
                    : 0;
            }

            return $d;
        });

        // ✅ собираем полные итоги, включая классы
        $totals = [
            'schools' => $districts->sum('schools_count'),
            'students' => $districts->sum('total_students'),
            'passed' => $districts->sum('passed_count'),
            'not_passed' => $districts->sum('not_passed_count'),
            'male_count' => $districts->sum('male_count'),
            'female_count' => $districts->sum('female_count'),
        ];

        foreach (range(1, 11) as $grade) {
            $totals['class' . $grade] = $districts->sum('class' . $grade . '_passed') ?? 0;
        }

        return [
            'rows' => $districts,
            'totals' => $totals,
        ];
    }


    /**
     * 🏫 Отчёт по школам выбранного района
     */
    public function getDistrictSummary(int $districtId): array
    {
        $schools = $this->reportRepository->getDistrictSummary($districtId);

        $schools = $schools->map(function ($s) {
            if (is_string($s->organization_title)) {
                $decoded = json_decode($s->organization_title, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $lang = app()->getLocale() ?? 'ru';
                    $s->organization_title = $decoded[$lang] ?? ($decoded['ru'] ?? reset($decoded));
                }
            }

            $s->passed_percent = $s->total_students > 0
                ? round(($s->passed_count / $s->total_students) * 100, 1)
                : 0;

            foreach (range(1, 11) as $grade) {
                $total = $s->{'class' . $grade . '_total'} ?? 0;
                $passed = $s->{'class' . $grade . '_passed'} ?? 0;
                $s->{'class' . $grade . '_percent'} = $total > 0
                    ? round(($passed / $total) * 100, 1)
                    : 0;
            }

            return $s;
        });

        // ✅ добавляем итоги по классам
        $totals = [
            'schools' => $schools->count(),
            'students' => $schools->sum('total_students'),
            'passed' => $schools->sum('passed_count'),
            'not_passed' => $schools->sum('not_passed_count'),
            'male_count' => $schools->sum('male_count'),
            'female_count' => $schools->sum('female_count'),
        ];

        foreach (range(1, 11) as $grade) {
            $totals['class' . $grade] = $schools->sum('class' . $grade . '_passed') ?? 0;
        }

        return [
            'rows' => $schools,
            'totals' => $totals,
        ];
    }

    /**
     * 🧠 Отчёт по методикам
     */
    public function getMethodicsSummary(int $regionId, ?int $districtId = null): array
    {
        $schools = $this->reportRepository->getMethodicsSummary($regionId, $districtId);

        $schools = $schools->map(function ($s) {
            if (is_string($s->organization_title)) {
                $decoded = json_decode($s->organization_title, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $lang = app()->getLocale() ?? 'ru';
                    $s->organization_title = $decoded[$lang] ?? ($decoded['ru'] ?? reset($decoded));
                }
            }

            $s->passed_percent = $s->total_students > 0
                ? round(($s->total_passed / $s->total_students) * 100, 1)
                : 0;

            return $s;
        });

        return [
            'rows' => $schools,
            'totals' => [
                'schools' => $schools->count(),
                'students' => $schools->sum('total_students'),
                'passed' => $schools->sum('total_passed'),
                'not_passed' => $schools->sum('total_not_passed'),
            ],
        ];
    }
}
