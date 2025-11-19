<?php

namespace App\Livewire\Components\Survey\Chart;

use Illuminate\View\View;
use Livewire\Component;

class AnxietyFloatingChartComponent extends Component
{
    public array $chartData = [];
    public function mount(array $chartData): void
    {
        $this->chartData = $chartData;
    }

    public function render(): View
    {
        return view('livewire.components.survey.chart.anxiety-floating-chart-component');
    }
}
