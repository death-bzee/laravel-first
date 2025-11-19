<?php

namespace App\Contracts\Survey;

interface SurveyInterpretationServiceContract
{
    /**
     * Генерирует интерпретацию результатов теста.
     *
     * @param int $surveyId
     * @param array $surveyJson
     * @return array
     */
    public function generateInterpretation(int $surveyId, array $surveyJson): array;
}
