<?php

namespace App\Contracts\Survey;

interface SurveyScalingServiceContract
{
    /**
     * Рассчитывает шкалирование на основе результатов теста.
     *
     * @param array $surveyJson
     * @return array
     */
    public function calculateScaling(array $surveyJson): array;
}
