<?php

namespace App\Services\Survey\Chart;

use Illuminate\Support\Collection;

abstract class SurveyChartDataService
{
    protected const array CHART_COLOR_PALETTE = [
        '#FF6384', // Красный
        '#36A2EB', // Синий
        '#FFCE56', // Желтый
        '#4BC0C0', // Бирюзовый
        '#9966FF', // Фиолетовый
        '#FF9F40', // Оранжевый
        '#2ecc71', // Зеленый
        '#e74c3c', // Алый
        '#3498db', // Голубой
        '#9b59b6', // Лавандовый
    ];

    public function getColorForAnxietyLevel(int $score): string
    {
        return match (true) {
            $score >= 15 => 'red',
            $score >= 10 => 'orange',
            $score >= 5 => 'blue',
            default => 'green',
        };
    }

    public function adjustTransparency(string $color, float $opacity): string
    {
        return match ($color) {
            'red' => "rgba(255, 99, 132, $opacity)",
            'orange' => "rgba(255, 165, 0, $opacity)",
            'blue' => "rgba(54, 162, 235, $opacity)",
            'green' => "rgba(75, 192, 192, $opacity)",
            default => "rgba(200, 200, 200, $opacity)"
        };
    }

    public function getColorByIndex(int $index): string
    {
        return self::CHART_COLOR_PALETTE[$index % count(self::CHART_COLOR_PALETTE)];
    }
}
