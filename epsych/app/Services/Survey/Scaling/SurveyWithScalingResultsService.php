<?php

namespace App\Services\Survey\Scaling;

use App\Models\Survey\Survey;
use App\Repositories\Survey\SurveyScalingDiagnosisRepository;
use Illuminate\Support\Collection;

class SurveyWithScalingResultsService
{
    public function __construct(
        protected SurveyScalingDiagnosisRepository $diagnosisRepository
    ) {}

    /**
     * Получить список опросов, по которым есть заполненное шкалирование у учеников из организации.
     */
    public function getSurveysByRegion(int $regionId): Collection
    {
        $surveyIds = collect($this->diagnosisRepository->getSurveyIdsWithScalingByRegion($regionId));

        if ($surveyIds->isEmpty()) {
            return collect();
        }

        return Survey::query()
            ->whereIn('id', $surveyIds)
            ->get();
    }

    /**
     * Получить список опросов, по которым есть заполненное шкалирование у учеников из района.
     */
    public function getSurveysByDistrict(int $districtId): Collection
    {
        $surveyIds = collect($this->diagnosisRepository->getSurveyIdsWithScalingByDistrict($districtId));

        if ($surveyIds->isEmpty()) {
            return collect();
        }

        return Survey::query()
            ->whereIn('id', $surveyIds)
            ->get();
    }

    /**
     * Получить список опросов, по которым есть заполненное шкалирование у учеников из организации.
     */
    public function getSurveysByOrganization(int $organizationId): Collection
    {
        $surveyIds = collect($this->diagnosisRepository->getSurveyIdsWithScalingByOrganization($organizationId));

        if ($surveyIds->isEmpty()) {
            return collect();
        }

        return Survey::query()
            ->whereIn('id', $surveyIds)
            ->get();
    }

    /**
     * Получить список опросов, по которым есть заполненное шкалирование у учеников из класса.
     */
    public function getSurveysByClassroom(int $classroomId): Collection
    {
        $surveyIds = $this->diagnosisRepository->getSurveyIdsWithScalingByClassroom($classroomId);

        if ($surveyIds->isEmpty()) {
            return collect();
        }

        return Survey::query()
            ->whereIn('id', $surveyIds)
            ->get();
    }
}
