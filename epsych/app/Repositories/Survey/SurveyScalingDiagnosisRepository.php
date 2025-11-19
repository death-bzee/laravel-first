<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveyStudentDiagnosis;
use Illuminate\Support\Collection;

class SurveyScalingDiagnosisRepository
{
    public function getDiagnosisByClassroomAndSurvey(int $classroomId, int $surveyId, ?int $organizationId = null): Collection
    {
        return SurveyStudentDiagnosis::query()
            ->whereHas('surveyAssignment.groupAssignment', fn($q) => $q->where('survey_id', $surveyId))
            ->whereHas('surveyAssignment.student', function ($q) use ($classroomId, $organizationId) {
                $q->where('classroom_id', $classroomId)
                    ->where('organization_id', $organizationId);
            })
            ->whereNotNull('scaling')
            ->whereRaw("JSON_LENGTH(scaling) > 0")
            ->with(['surveyAssignment.student', 'surveyAssignment.groupAssignment.classroom'])
            ->get();
    }


    public function getDiagnosisByOrganizationAndSurvey(int $organizationId, int $surveyId): Collection
    {
        return SurveyStudentDiagnosis::query()
            ->whereHas('surveyAssignment.groupAssignment', fn($query) => $query->where('survey_id', $surveyId))
            ->whereHas('surveyAssignment.groupAssignment.organization', fn($query) => $query->where('id', $organizationId))
            ->whereNotNull('scaling')
            ->whereRaw('JSON_LENGTH(scaling) > 0')
            ->with(['surveyAssignment.student', 'surveyAssignment.groupAssignment.classroom'])
            ->get();
    }

    public function getDiagnosisByDistrictAndSurvey(int $districtId, int $surveyId): Collection
    {
        return SurveyStudentDiagnosis::query()
            ->whereHas('surveyAssignment.groupAssignment', fn($query) => $query->where('survey_id', $surveyId))
            ->whereHas('surveyAssignment.groupAssignment.organization', fn($query) => $query->where('district_id', $districtId))
            ->whereNotNull('scaling')
            ->whereRaw('JSON_LENGTH(scaling) > 0')
            ->with(['surveyAssignment.student', 'surveyAssignment.groupAssignment.classroom'])
            ->get();
    }

    public function getDiagnosisByRegionAndSurvey(int $regionId, int $surveyId): Collection
    {
        return SurveyStudentDiagnosis::query()
            ->whereHas('surveyAssignment.groupAssignment', fn($query) => $query->where('survey_id', $surveyId))
            ->whereHas('surveyAssignment.groupAssignment.organization', fn($query) => $query->where('region_id', $regionId))
            ->whereNotNull('scaling')
            ->whereRaw('JSON_LENGTH(scaling) > 0')
            ->with(['surveyAssignment.student', 'surveyAssignment.groupAssignment.classroom'])
            ->get();
    }

    public function getSurveyIdsWithScalingByDistrict(int $districtId): Collection
    {
        return SurveyStudentDiagnosis::query()
            ->whereHas('surveyAssignment.groupAssignment.organization', fn($query) => $query->where('district_id', $districtId))
            ->whereNotNull('scaling')
            ->whereRaw('JSON_LENGTH(scaling) > 0')
            ->join('survey_assignments', 'survey_assignments.id', '=', 'survey_student_diagnoses.survey_assignment_id')
            ->join('survey_group_assignments', 'survey_group_assignments.id', '=', 'survey_assignments.group_id')
            ->pluck('survey_group_assignments.survey_id')
            ->unique();
    }

    public function getSurveyIdsWithScalingByRegion(int $regionId): Collection
    {
        return SurveyStudentDiagnosis::query()
            ->whereHas('surveyAssignment.groupAssignment.organization', fn($query) => $query->where('region_id', $regionId))
            ->whereNotNull('scaling')
            ->whereRaw('JSON_LENGTH(scaling) > 0')
            ->join('survey_assignments', 'survey_assignments.id', '=', 'survey_student_diagnoses.survey_assignment_id')
            ->join('survey_group_assignments', 'survey_group_assignments.id', '=', 'survey_assignments.group_id')
            ->pluck('survey_group_assignments.survey_id')
            ->unique();
    }

    public function getSurveyIdsWithScalingByOrganization(int $organizationId): Collection
    {
        return SurveyStudentDiagnosis::query()
            ->whereHas('surveyAssignment.groupAssignment.organization', fn($query) => $query->where('id', $organizationId))
            ->whereNotNull('scaling')
            ->whereRaw('JSON_LENGTH(scaling) > 0')
            ->join('survey_assignments', 'survey_assignments.id', '=', 'survey_student_diagnoses.survey_assignment_id')
            ->join('survey_group_assignments', 'survey_group_assignments.id', '=', 'survey_assignments.group_id')
            ->pluck('survey_group_assignments.survey_id')
            ->unique();
    }

    public function getSurveyIdsWithScalingByClassroom(int $classroomId): Collection
    {
        return SurveyStudentDiagnosis::query()
            ->whereHas('surveyAssignment.groupAssignment', fn($query) => $query->where('classroom_id', $classroomId))
            ->whereNotNull('scaling')
            ->whereRaw('JSON_LENGTH(scaling) > 0')
            ->join('survey_assignments', 'survey_assignments.id', '=', 'survey_student_diagnoses.survey_assignment_id')
            ->join('survey_group_assignments', 'survey_group_assignments.id', '=', 'survey_assignments.group_id')
            ->pluck('survey_group_assignments.survey_id')
            ->unique();
    }
}
