<?php

namespace App\Services\Survey\Chart;

use Illuminate\Support\Collection;

class GroupAnxietyChartDataService extends SurveyChartDataService
{
    /**
     * Подготавливает общие данные для линейного графика.
     */
    public function prepareFloatingChartData(Collection $scalingData, string $flag): array
    {
        $labels = [];
        $datasets = [];

        $allScales = collect($scalingData)
            ->flatMap(fn ($student) => collect($student['scalingData'])
                ->where($flag, true)
                ->map(fn ($item) => [
                    'scaleName' => $item['scaleName'],
                    'levelName' => $item['levelName'] ?? '',
                ])
            )
            ->unique('scaleName')
            ->values();

        foreach ($allScales as $scale) {
            $dataset = [
                'label' => $scale['scaleName'].($scale['levelName'] ? " ({$scale['levelName']})" : ''),
                'data' => [],
                'backgroundColor' => [],
                'borderColor' => [],
                'borderWidth' => 1,
            ];

            foreach ($scalingData as $student) {
                $fullName = "{$student['studentFullName']} (".__('Класс')." {$student['classroomName']})";

                if (! in_array($fullName, $labels)) {
                    $labels[] = $fullName;
                }

                $anxietyData = collect($student['scalingData'])
                    ->where($flag, true)
                    ->firstWhere('scaleName', $scale['scaleName']);

                if ($anxietyData) {
                    $score = $anxietyData['score'] ?? 0;
                    $dataset['data'][] = [0, $score];

                    $color = $this->getColorForAnxietyLevel($score);
                    $dataset['backgroundColor'][] = $this->adjustTransparency($color, 0.9);
                    $dataset['borderColor'][] = $color;
                } else {
                    $dataset['data'][] = [0, 0];
                    $dataset['backgroundColor'][] = 'rgba(200, 200, 200, 0.3)';
                    $dataset['borderColor'][] = 'rgba(150, 150, 150, 0.8)';
                }
            }

            $datasets[] = $dataset;
        }

        if (! $datasets) {
            return [];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

}
