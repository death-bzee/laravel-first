import Chart from "chart.js/auto";
import {chartInstances, setupChartInstance} from "./chartUtils.js";

export function initAnxietyFloatingChart(component, chartId) {
	const ctx = setupChartInstance(component, chartId);
	if (!ctx) return;

	const chartData = component.$wire.get("chartData");
	console.log("chartData:", chartData);
	if (!chartData || !chartData.labels?.length) return;

	const ROW_HEIGHT = 30;
	const totalHeight = chartData.labels.length * ROW_HEIGHT;
	const container = ctx.closest("[data-chart-container]");

	if (container) {
		container.style.height = `${totalHeight}px`;
	}

	const chart = new Chart(ctx, {
		type: "bar",
		data: {
			labels: chartData.labels,
			datasets: [
				{
					label: chartData.datasets[0]?.label || "Общая тревожность",
					data: chartData.datasets[0]?.data || [],
					backgroundColor:
						chartData.datasets[0]?.backgroundColor || "rgba(200, 200, 200, 0.5)",
					borderColor:
						chartData.datasets[0]?.borderColor || "rgba(0, 0, 0, 0.8)",
					borderWidth: 1,
				},
			],
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			indexAxis: "y",
			maxBarThickness: 40, // Максимальная толщина столбца
			categoryPercentage: 0.8, // Пространство, занимаемое категорией
			barPercentage: 0.9, // Пространство, занимаемое столбцом внутри категории
			plugins: {
				title: {
					display: true,
					text: chartData.datasets[0]?.label || "Общая тревожность",
				},
				legend: {
					display: false,
				},
				datalabels: {
					color: '#fff',
					formatter: (value) => {
						if (Array.isArray(value)) {
							return value[1]; // Показывать верхнее значение столбца
						}
						return value ?? '';
					},
					font: {
						weight: 'bold',
						size: 12,
					},
				},
			},
			scales: {
				x: {
					beginAtZero: false,
					title: {
						display: true,
						text: "Баллы",
					},
				},
				y: {
					ticks: {
						autoSkip: false,
					},
				},
			},
		},
	});

	// Теперь переменная chartInstances доступна, так как мы её импортировали
	chartInstances.set(component.id, chart);
}
