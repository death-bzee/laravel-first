import Chart from "chart.js/auto";
import { setupChartInstance, chartInstances } from "./chartUtils.js";
import ChartDataLabels from 'chartjs-plugin-datalabels';
Chart.register(ChartDataLabels);

function getCurrentLang() {
    if (window.appLang) return window.appLang;

    const meta = document.querySelector('meta[name="app-lang"]');
    if (meta) return meta.content;

    return document.documentElement.lang || 'ru';
}

export function initAnxietyChart(component, chartId, type = 'pie') {
    const ctx = setupChartInstance(component, chartId);
    if (!ctx) return;

    // 🛑 Убиваем старый график, если был
    const existingChart = chartInstances.get(component.id);
    if (existingChart) {
        existingChart.destroy();
        chartInstances.delete(component.id);
    }

    const chartTitles = {
        ru: "Распределение тревожности по критериям",
        kk: "Мазасыздықты критерийлер бойынша бөлу"
    };


    const chartData = component.$wire.get("chartData");

    const chart = new Chart(ctx, {
        type: type,
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    data: chartData.datasets[0]?.data || [],
                    backgroundColor: chartData.datasets[0]?.backgroundColor || [],
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: chartTitles[getCurrentLang()] || chartTitles['ru'],
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            return `${label}: ${value}%`;
                        }
                    }
                },
                legend: type === 'bar'
                    ? { display: false } // ⬅️ Легенду скрываем для bar
                    : {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                        },
                    },
                datalabels: {
                    color: '#fff',
                    formatter: (value) => `${value}%`,
                    font: {
                        weight: 'bold',
                        size: 12,
                    },
                },
            },
        },
    });

    chartInstances.set(component.id, chart);
}
