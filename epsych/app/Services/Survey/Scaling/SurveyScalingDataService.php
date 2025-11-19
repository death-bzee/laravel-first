<?php

namespace App\Services\Survey\Scaling;

use App\Data\Survey\Scaling\ScalingAggregationContextData;
use App\Models\Classroom;
use App\Models\Student;
use App\Repositories\Survey\SurveyScalingDiagnosisRepository;
use Illuminate\Support\Collection;

class SurveyScalingDataService
{
    public function __construct(
        protected SurveyScalingDiagnosisRepository $surveyScalingDiagnosisRepository
    ) {}

    public function aggregateByUserContext(ScalingAggregationContextData $context): Collection
    {
        $user = auth()->user();

        if ($user->hasRole('correctional_service_region')) {
            return $context->districtId === 0
                ? $this->aggregateByRegion($user->region_id, $context->surveyId)
                : $this->aggregateByDistrict($context->districtId, $context->surveyId);
        }

        if ($user->hasRole('correctional_service_district')) {
            return $context->organizationId === 0
                ? $this->aggregateByDistrict($user->district_id, $context->surveyId)
                : $this->aggregateByOrganization($context->organizationId, $context->surveyId);
        }

        if ($user->hasAnyRole(['psychologist', 'social_pedagogue'])) {
            return $context->classroomId === 0
                ? $this->aggregateByOrganization($user->organization_id, $context->surveyId)
                : $this->aggregateByClassroom($context->classroomId, $context->surveyId);
        }

        return collect();
    }

    public function aggregateByClassroom(int $classroomId, int $surveyId): Collection
    {
        $diagnoses = $this->surveyScalingDiagnosisRepository->getDiagnosisByClassroomAndSurvey($classroomId, $surveyId,  auth()->user()->organization_id);

        return $this->transformDiagnoses($diagnoses);
    }

    public function aggregateByOrganization(int $organizationId, int $surveyId): Collection
    {
        $diagnoses = $this->surveyScalingDiagnosisRepository->getDiagnosisByOrganizationAndSurvey($organizationId, $surveyId);

        return $this->transformDiagnoses($diagnoses);
    }

    public function aggregateByDistrict(int $districtId, int $surveyId): Collection
    {
        $diagnoses = $this->surveyScalingDiagnosisRepository->getDiagnosisByDistrictAndSurvey($districtId, $surveyId);

        return $this->transformDiagnoses($diagnoses);
    }

    public function aggregateByRegion(int $regionId, int $surveyId): Collection
    {
        $diagnoses = $this->surveyScalingDiagnosisRepository->getDiagnosisByRegionAndSurvey($regionId, $surveyId);

        return $this->transformDiagnoses($diagnoses);
    }

    private function transformDiagnoses(Collection $diagnoses): Collection
    {
        $lang = app()->getLocale();

        return $diagnoses
            ->groupBy(fn($diagnosis) => $diagnosis->surveyAssignment->student->id ?? 'unknown')
            ->map(function ($diagnoses) use ($lang) {
                $first = $diagnoses->first();

                /** @var Student|null $student */
                $student = $first->surveyAssignment->student ?? null;

                /** @var Classroom|null $classroom */
                $classroom = $first->surveyAssignment->groupAssignment->classroom ?? null;

                return [
                    'studentId' => $student?->id ?? '',
                    'studentOrganizationId' => $student?->organization_id ?? '',
                    'studentFullName' => $student?->fullName ?? '',
                    'classroomName' => $classroom?->classroomName ?? '',
                    'studentClassroomId' => $classroom?->id,
                    'scalingData' => $diagnoses->flatMap(
                        fn($diagnosis)  => collect($diagnosis
                            ->getTranslation('scaling', $lang))
                            ->map(fn($item)  => [
                                'isGeneral' => $item['isGeneral'] ?? false,
                                'isRisk' => $item['isRisk'] ?? false,
                                'scaleName' => $item['scaleName'] ?? '',
                                'levelName' => $item['levelName'] ?? '',
                                'score' => $item['score'] ?? 0,
                            ])
                    ),
                ];
            })
            ->values();
    }
}
