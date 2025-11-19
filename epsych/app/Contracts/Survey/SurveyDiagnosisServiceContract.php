<?php

namespace App\Contracts\Survey;

use Illuminate\Support\Collection;

interface SurveyDiagnosisServiceContract
{
    /**
     * Агрегирует данные шкалирования из JSON и собирает коллекцию.
     *
     * @param int $classroomId
     * @param int $surveyId
     * @return Collection
     */

    public function aggregateSurveyScalingData(int $classroomId, int $surveyId): Collection;
    /**
     * Метод для генерации ответа на результаты тестирования.
     *
     * @param array $surveyJson
     * @return array
     */
    public function generateStudentDiagnosis(array $surveyJson): array;
}
