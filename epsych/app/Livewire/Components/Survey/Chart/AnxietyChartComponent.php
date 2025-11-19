<?php

namespace App\Livewire\Components\Survey\Chart;

use Illuminate\View\View;
use Livewire\Component;

class AnxietyChartComponent extends Component
{
    public array $chartData = [];

    public string $typeDiagram = 'pie';

    public function mount(array $chartData, $typeDiagram): void
    {
        $this->chartData = $chartData;

        $this->typeDiagram = $typeDiagram;
    }

    public function render(): View
    {
        return view('livewire.components.survey.chart.anxiety-chart-component', [
            'chartData' => $this->chartData,
            'typeDiagram' => $this->typeDiagram,
        ]);
    }
}
