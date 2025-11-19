<?php

namespace App\Services\Survey\Report;

use App\Contracts\Survey\Report\SurveyReportContract;
use App\Data\Survey\Report\SurveyReportData;
use App\Exports\Survey\OlweusReportExport;
use App\Models\Classroom;
use App\Models\Organization;
use App\Models\Student;
use App\Models\Survey\Survey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OlweusSurveyReport implements SurveyReportContract
{
    protected Collection $scalingData;
    protected ?int $classroomId = null;
    protected ?int $surveyId = null;

    public function supports(Survey $survey): bool
    {
        $title = $survey->getTranslation('title', app()->getLocale());

        return str_contains($title, 'Буллинг') || str_contains($title, 'Олвеус');
    }

    public function build(Collection $scalingData, array $options = []): SurveyReportData
    {
        $this->scalingData = $scalingData;
        $this->classroomId = $options['classroom_id'] ?? null;
        $this->surveyId    = $options['survey_id'] ?? null;

        $role           = $options['role'] ?? null;
        $districtId     = $options['district_id'] ?? null;
        $organizationId = $options['organization_id'] ?? null;
        /** @var \App\Models\User|null $user */
        $user           = $options['user'] ?? null;

        $reportData      = [];
        $classesSummary  = [];
        $testedByClass   = [];
        $schoolTotal     = null;
        $totals          = [];

        // === ЛОГИКА, КОТОРАЯ БЫЛА В КОМПОНЕНТЕ ===

        // 🔹 Районный уровень
        if ($role === 'district') {

            // если школа не выбрана → раньше было "слишком много данных"
            if ((int)$organizationId === 0) {
                // возвращаем пустой отчёт, компонент сам покажет уведомление
                return new SurveyReportData();
            }

            // одна школа, БЕЗ классов
            $organizationId = (int) $organizationId;
            $schoolName = Organization::find($organizationId)->title ?? 'Школа';

            $oneSchool = $this->buildOlweusReportByOneSchool($organizationId);

            $reportData = [
                $organizationId => [
                    'name'             => $schoolName,
                    'total_students'   => $oneSchool['total_students'],
                    'tested_students'  => $oneSchool['tested_students'],
                    'direct_active'    => $oneSchool['direct_active'],
                    'indirect_active'  => $oneSchool['indirect_active'],
                    'direct_passive'   => $oneSchool['direct_passive'],
                    'indirect_passive' => $oneSchool['indirect_passive'],
                ],
            ];

            $schoolTotal = $oneSchool['total_students'];

            $classesSummary = [
                [
                    'classroom_id'   => $organizationId,
                    'classroom_name' => $schoolName,
                    'students_count' => $oneSchool['total_students'],
                ],
            ];

            $testedByClass = [
                $schoolName => $oneSchool['tested_students'],
            ];

            $totals = $this->calculateTotals($reportData);

            return new SurveyReportData(
                reportData: $reportData,
                classesSummary: $classesSummary,
                testedByClass: $testedByClass,
                schoolTotalStudents: $schoolTotal,
                totals: $totals,
                exportClass: OlweusReportExport::class,
                exportTitle: 'Д. Олвеус — сводные данные по буллингу',
                bladeView: 'olweus-report'
            );
        }

        // 🔹 Психолог / социальный педагог — отчёт по классам школы
        if ($role === 'psychologist' && $organizationId) {

            $reportData = $this->buildOlweusReport($organizationId);
            $classesSummary = $this->getClassesWithStudentCount($organizationId);
            $totals = $this->calculateTotals($reportData);
            $testedByClass = $this->getTestedByClass($organizationId, $this->surveyId);

            // Фильтрация по выбранному классу
            if ($this->classroomId) {
                $className = Classroom::find($this->classroomId)?->classroom_full_name;

                if ($className) {
                    $reportData = array_filter(
                        $reportData,
                        fn($row) => $row['name'] == $className
                    );

                    $classesSummary = array_filter(
                        $classesSummary,
                        fn($c) => $c['classroom_name'] == $className
                    );

                    $testedByClass = array_filter(
                        $testedByClass,
                        fn($key) => $key == $className,
                        ARRAY_FILTER_USE_KEY
                    );

                    $totals = $this->calculateTotals($reportData);
                }
            }

            $schoolTotal = array_sum(array_column($classesSummary, 'students_count'));

            return new SurveyReportData(
                reportData: $reportData,
                classesSummary: $classesSummary,
                testedByClass: $testedByClass,
                schoolTotalStudents: $schoolTotal,
                totals: $totals,
                exportClass: OlweusReportExport::class,
                exportTitle: 'Д. Олвеус — сводные данные по буллингу',
                bladeView: 'olweus-report'
            );
        }

        // 🔹 Область — по районам можно тоже сделать тут позже (аналог buildOlweusReportBySchools)

        return new SurveyReportData();
    }

    // ======= ДАЛЬШЕ — ТВOИ ПЕРЕНЕСЁННЫЕ МЕТОДЫ =======

    private function buildOlweusReport(?int $organizationId = null): array
    {
        $report = [];

        // Считаем общее кол-во учеников школы / класса
        if ($this->classroomId > 0) {
            $schoolTotalStudents = Student::where('organization_id', $organizationId)
                ->where('classroom_id', $this->classroomId)
                ->count();
        } else {
            $schoolTotalStudents = Student::where('organization_id', $organizationId)->count();
        }

        foreach ($this->scalingData as $student) {

            if (
                $this->classroomId > 0 &&
                $student['studentClassroomId'] != $this->classroomId
            ) {
                continue;
            }

            $groupKey  = $student['studentClassroomId'];
            $groupName = $student['classroomName'] ?? '—';

            $totalStudents = Student::where('classroom_id', $groupKey)->count();

            if (! isset($report[$groupKey])) {
                $report[$groupKey] = [
                    'name'            => $groupName,
                    'total_students'  => $totalStudents,
                    'tested_students' => 0,
                    'direct_active'    => ['weak' => 0, 'medium' => 0, 'strong' => 0],
                    'indirect_active'  => ['weak' => 0, 'medium' => 0, 'strong' => 0],
                    'direct_passive'   => ['weak' => 0, 'medium' => 0, 'strong' => 0],
                    'indirect_passive' => ['weak' => 0, 'medium' => 0, 'strong' => 0],
                ];
            }

            $report[$groupKey]['tested_students']++;

            static $counted = [];

            foreach ($student['scalingData'] as $scale) {

                $scaleKey = $this->normalizeScaleKey($scale['scaleName']);
                $levelKey = $this->normalizeLevelKey($scale['levelName']);

                if (! $scaleKey || ! $levelKey) {
                    continue;
                }

                $studentId = $student['studentId'];

                if (! isset($counted[$groupKey][$scaleKey][$levelKey][$studentId])) {
                    $report[$groupKey][$scaleKey][$levelKey]++;
                    $counted[$groupKey][$scaleKey][$levelKey][$studentId] = true;
                }
            }
        }

        uasort($report, function ($a, $b) {
            return strnatcasecmp($a['name'], $b['name']);
        });

        return $report;
    }

    private function buildOlweusReportByOneSchool(int $organizationId): array
    {
        $totalStudents = Student::where('organization_id', $organizationId)->count();

        $tested = DB::table('survey_student_diagnoses as sd')
            ->join('survey_assignments as sa', 'sa.id', '=', 'sd.survey_assignment_id')
            ->join('survey_group_assignments as sga', 'sga.id', '=', 'sa.group_id')
            ->join('students as s', 's.id', '=', 'sa.student_id')
            ->where('s.organization_id', $organizationId)
            ->where('sga.survey_id', $this->surveyId)
            ->count();

        $result = [
            'total_students'   => $totalStudents,
            'tested_students'  => $tested,
            'direct_active'    => ['weak' => 0, 'medium' => 0, 'strong' => 0],
            'indirect_active'  => ['weak' => 0, 'medium' => 0, 'strong' => 0],
            'direct_passive'   => ['weak' => 0, 'medium' => 0, 'strong' => 0],
            'indirect_passive' => ['weak' => 0, 'medium' => 0, 'strong' => 0],
        ];

        foreach ($this->scalingData as $student) {
            foreach ($student['scalingData'] as $scale) {
                $scaleKey = $this->normalizeScaleKey($scale['scaleName']);
                $levelKey = $this->normalizeLevelKey($scale['levelName']);

                if ($scaleKey && $levelKey) {
                    $result[$scaleKey][$levelKey]++;
                }
            }
        }

        return $result;
    }

    private function calculateTotals(array $report): array
    {
        $totals = [
            'students'        => 0,
            'tested'          => 0,
            'direct_active'   => ['weak' => 0, 'medium' => 0, 'strong' => 0],
            'indirect_active' => ['weak' => 0, 'medium' => 0, 'strong' => 0],
            'direct_passive'  => ['weak' => 0, 'medium' => 0, 'strong' => 0],
            'indirect_passive' => ['weak' => 0, 'medium' => 0, 'strong' => 0],
        ];

        foreach ($report as $r) {
            $totals['students'] += $r['total_students'];
            $totals['tested']   += $r['tested_students'];

            foreach (['direct_active', 'indirect_active', 'direct_passive', 'indirect_passive'] as $scale) {
                foreach (['weak', 'medium', 'strong'] as $level) {
                    $totals[$scale][$level] += $r[$scale][$level] ?? 0;
                }
            }
        }

        return $totals;
    }

    private function getClassesWithStudentCount(int $organizationId): array
    {
        $studentsByClass = Student::query()
            ->select('classroom_id')
            ->where('organization_id', $organizationId)
            ->get()
            ->groupBy('classroom_id');

        $result = [];

        $kazakhAlphabet = [
            'А',
            'Ә',
            'Б',
            'В',
            'Г',
            'Ғ',
            'Д',
            'Е',
            'Ё',
            'Ж',
            'З',
            'И',
            'Й',
            'К',
            'Қ',
            'Л',
            'М',
            'Н',
            'Ң',
            'О',
            'Ө',
            'П',
            'Р',
            'С',
            'Т',
            'У',
            'Ұ',
            'Ү',
            'Ф',
            'Х',
            'Һ',
            'Ц',
            'Ч',
            'Ш',
            'Щ',
            'Ы',
            'І',
            'Э',
            'Ю',
            'Я',
        ];

        foreach ($studentsByClass as $classroomId => $students) {

            $classroom = Classroom::find($classroomId);

            $name = $classroom?->classroom_full_name ?? '—';

            preg_match('/^(\d+)\s*(.+)$/u', $name, $m);

            $grade  = isset($m[1]) ? (int) $m[1] : 0;
            $letter = isset($m[2]) ? trim($m[2]) : '';

            $letterIndex = array_search(mb_strtoupper($letter), $kazakhAlphabet);

            if ($letterIndex === false) {
                $letterIndex = 999;
            }

            $result[] = [
                'classroom_id'   => $classroomId,
                'classroom_name' => $name,
                'students_count' => $students->count(),
                'grade'          => $grade,
                'letter'         => $letter,
                'letter_index'   => $letterIndex,
            ];
        }

        usort($result, function ($a, $b) {
            if ($a['grade'] !== $b['grade']) {
                return $a['grade'] <=> $b['grade'];
            }
            return $a['letter_index'] <=> $b['letter_index'];
        });

        return $result;
    }

    private function getTestedByClass(int $organizationId, int $surveyId): array
    {
        return DB::table('survey_student_diagnoses as sd')
            ->join('survey_assignments as sa', 'sa.id', '=', 'sd.survey_assignment_id')
            ->join('survey_group_assignments as sga', 'sga.id', '=', 'sa.group_id')
            ->join('students as s', 's.id', '=', 'sa.student_id')
            ->join('classrooms as c', 'c.id', '=', 's.classroom_id')
            ->where('s.organization_id', $organizationId)
            ->where('sga.survey_id', $surveyId)
            ->selectRaw('c.classroom_full_name as name, COUNT(DISTINCT s.id) as tested_count')
            ->groupBy('c.classroom_full_name')
            ->pluck('tested_count', 'name')
            ->toArray();
    }

    private function normalizeScaleKey(string $name): ?string
    {
        $name = mb_strtolower(trim($name));

        $map = config('olweus.mapping');

        foreach ($map as $pattern => $key) {
            if (str_contains($name, $pattern)) {
                return $key;
            }
        }

        if (str_contains($name, 'активн') || str_contains($name, 'белсенді')) {
            return str_contains($name, 'жанама') ? 'indirect_active' : 'direct_active';
        }

        if (str_contains($name, 'пассивн') || str_contains($name, 'пассивті') || str_contains($name, 'виктим')) {
            return str_contains($name, 'жанама') ? 'indirect_passive' : 'direct_passive';
        }

        return null;
    }

    private function normalizeLevelKey(string $level): ?string
    {
        $level = mb_strtolower($level);

        return match (true) {
            str_contains($level, 'слаб'), str_contains($level, 'әлсіз') => 'weak',
            str_contains($level, 'умерен'), str_contains($level, 'орташа') => 'medium',
            str_contains($level, 'ярк'), str_contains($level, 'айқын') => 'strong',
            default => null,
        };
    }
}
