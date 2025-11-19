<?php

namespace App\Repositories\Survey;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SurveyReportRepository
{
    /**
     * 📊 Отчёт по районам региона
     */
    public function getRegionSummary(int $regionId): Collection
    {
        $gradeSelects = collect(range(1, 11))
            ->map(fn($grade) => "
                SUM(CASE WHEN classrooms.grade = {$grade}
                         AND survey_student_diagnoses.id IS NOT NULL
                    THEN 1 ELSE 0 END) AS class{$grade}_passed,
                SUM(CASE WHEN classrooms.grade = {$grade} THEN 1 ELSE 0 END) AS class{$grade}_total
            ")
            ->implode(',');

        return DB::table('districts')
            ->selectRaw("
                districts.id as district_id,
                districts.title as district_title,
                COUNT(DISTINCT organizations.id) as schools_count,
                COUNT(students.id) as total_students,
                COUNT(CASE WHEN survey_student_diagnoses.id IS NOT NULL THEN students.id END) as passed_count,
                COUNT(CASE WHEN survey_student_diagnoses.id IS NULL THEN students.id END) as not_passed_count,
                COUNT(CASE WHEN LOWER(students.gender) IN ('male','мужской') THEN 1 END) AS male_count,
                COUNT(CASE WHEN LOWER(students.gender) IN ('female','женский') THEN 1 END) AS female_count,
                {$gradeSelects}
            ")
            ->leftJoin('organizations', 'organizations.district_id', '=', 'districts.id')
            ->leftJoin('students', 'students.organization_id', '=', 'organizations.id')
            ->leftJoin('classrooms', 'classrooms.id', '=', 'students.classroom_id')
            ->leftJoin('survey_assignments', 'survey_assignments.student_id', '=', 'students.id')
            ->leftJoin('survey_student_diagnoses', 'survey_student_diagnoses.survey_assignment_id', '=', 'survey_assignments.id')
            ->where('districts.region_id', $regionId)
            ->groupBy('districts.id', 'districts.title')
            ->orderBy('districts.title')
            ->get();
    }

    /**
     * 🏫 Отчёт по школам конкретного района
     */
    public function getDistrictSummary(int $districtId): Collection
    {
        $gradeSelects = collect(range(1, 11))
            ->map(fn($grade) => "
                SUM(CASE WHEN classrooms.grade = {$grade}
                         AND survey_student_diagnoses.id IS NOT NULL
                    THEN 1 ELSE 0 END) AS class{$grade}_passed,
                SUM(CASE WHEN classrooms.grade = {$grade} THEN 1 ELSE 0 END) AS class{$grade}_total
            ")
            ->implode(',');

        return DB::table('organizations')
            ->selectRaw("
                organizations.id as organization_id,
                organizations.title as organization_title,
                COUNT(students.id) as total_students,
                COUNT(CASE WHEN survey_student_diagnoses.id IS NOT NULL THEN students.id END) as passed_count,
                COUNT(CASE WHEN survey_student_diagnoses.id IS NULL THEN students.id END) as not_passed_count,
                COUNT(CASE WHEN LOWER(students.gender) IN ('male','мужской') THEN 1 END) AS male_count,
                COUNT(CASE WHEN LOWER(students.gender) IN ('female','женский') THEN 1 END) AS female_count,
                {$gradeSelects}
            ")
            ->leftJoin('students', 'students.organization_id', '=', 'organizations.id')
            ->leftJoin('classrooms', 'classrooms.id', '=', 'students.classroom_id')
            ->leftJoin('survey_assignments', 'survey_assignments.student_id', '=', 'students.id')
            ->leftJoin('survey_student_diagnoses', 'survey_student_diagnoses.survey_assignment_id', '=', 'survey_assignments.id')
            ->where('organizations.district_id', $districtId)
            ->groupBy('organizations.id', 'organizations.title')
            ->orderBy('organizations.title')
            ->get();
    }

    /**
     * 🧠 Отчёт по методикам (по области или району)
     */
    public function getMethodicsSummary(int $regionId, ?int $districtId = null): Collection
    {
        $methodics = DB::table('surveys')->select('id', 'title')->orderBy('id')->get();

        $methodicSelects = $methodics->map(fn($m) => "
            COUNT(DISTINCT CASE WHEN sga.survey_id = {$m->id} THEN c.id END) AS methodic_{$m->id}_classes,
            COUNT(DISTINCT CASE WHEN sga.survey_id = {$m->id} THEN st.id END) AS methodic_{$m->id}_students,
            COUNT(DISTINCT CASE
                WHEN sga.survey_id = {$m->id}
                     AND (
                         JSON_SEARCH(ssd.scaling, 'one', 'true', NULL, '$.ru[*].isRisk') IS NOT NULL
                         OR JSON_SEARCH(ssd.scaling, 'one', 'true', NULL, '$.kk[*].isRisk') IS NOT NULL
                     )
                THEN st.id
            END) AS methodic_{$m->id}_risk_students
        ")->implode(',');

        $query = DB::table('organizations AS o')
            ->selectRaw("
                o.id AS organization_id,
                o.title AS organization_title,
                COUNT(st.id) AS total_students,
                COUNT(CASE WHEN ssd.id IS NOT NULL THEN st.id END) AS total_passed,
                COUNT(CASE WHEN ssd.id IS NULL THEN st.id END) AS total_not_passed,
                {$methodicSelects}
            ")
            ->leftJoin('students AS st', 'st.organization_id', '=', 'o.id')
            ->leftJoin('classrooms AS c', 'c.id', '=', 'st.classroom_id')
            ->leftJoin('survey_assignments AS sa', 'sa.student_id', '=', 'st.id')
            ->leftJoin('survey_group_assignments AS sga', 'sga.id', '=', 'sa.group_id')
            ->leftJoin('survey_student_diagnoses AS ssd', 'ssd.survey_assignment_id', '=', 'sa.id')
            ->groupBy('o.id', 'o.title')
            ->orderBy('o.title');

        if ($districtId) {
            $query->where('o.district_id', $districtId);
        } else {
            $query->leftJoin('districts AS d', 'd.id', '=', 'o.district_id')
                ->where('d.region_id', $regionId);
        }

        return $query->get();
    }
}
