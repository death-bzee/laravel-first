<?php

namespace App\Services\Survey\Chart;

use Illuminate\Support\Collection;

class AnxietyChartDataService extends SurveyChartDataService
{
    /**
     * Подготавливает данные для круговой диаграммы тревожности по критериям.
     */
    public function prepareAnxietyChartData(Collection $scalingData): array
    {
        $criteriaScores = [];
        $levelNames = [];

        foreach ($scalingData as $student) {
            foreach ($student['scalingData'] as $anxietyData) {
                if ($anxietyData['isGeneral']) {
                    continue;
                }

                $scaleName = $anxietyData['scaleName'];
                $score = $anxietyData['score'] ?? 0;
                $levelName = $anxietyData['levelName'] ?? null;

                if (! isset($criteriaScores[$scaleName])) {
                    $criteriaScores[$scaleName] = 0;
                }

                $criteriaScores[$scaleName] += $score;

                // Сохраняем последний levelName
                if ($levelName) {
                    $levelNames[$scaleName] = $levelName;
                }
            }
        }

        $totalScore = array_sum($criteriaScores);

        $labels = [];
        $data = [];
        $backgroundColors = [];
        $i = 0;

        foreach ($criteriaScores as $scaleName => $score) {
            $percentage = $totalScore > 0 ? round(($score / $totalScore) * 100, 1) : 0;

            $label = $scaleName;
            if (isset($levelNames[$scaleName])) {
                $label .= ' ('.$levelNames[$scaleName].')';
            }

            $labels[] = $label;
            $data[] = $percentage;
            $backgroundColors[] = $this->getColorByIndex($i);
            $i++;
        }

        if (!$data) {
            return [];
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
        ];
    }
}
