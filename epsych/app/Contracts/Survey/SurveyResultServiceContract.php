<?php

namespace App\Contracts\Survey;

interface SurveyResultServiceContract
{
    /**
     * Получить результаты опроса в формате JSON.
     *
     * @param int $surveyAssignmentId
     * @return array
     */
    public function getSurveyResultJson(int $surveyAssignmentId): array;
}
