<?php

namespace App\Contracts\Survey\Chart;

interface SurveyChartDataServiceContract
{
    public function getColorForAnxietyLevel(int $score): string;
    public function adjustTransparency(string $color, float $opacity): string;
}
