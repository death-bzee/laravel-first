import {initAnxietyFloatingChart} from "./anxietyFloatingChart.js";
import {initAnxietyChart} from "./anxietyChart.js";

Livewire.hook("component.init", ({component}) => {
    switch (component.name) {
        case "components.survey.chart.anxiety-floating-chart-component":
            const canvasFloatingId = `anxiety-floating-chart-${component.id}`;
            initAnxietyFloatingChart(component, canvasFloatingId);
            break;
        case "components.survey.chart.anxiety-chart-component":
            const canvasId = `anxiety-chart-${component.id}`;
            const type = document.getElementById(canvasId)?.dataset.type || 'pie';
            initAnxietyChart(component, canvasId, type);
            break;

        // Можно добавить другие графики или действия для других компонентов
        case "components.some-other-chart-component":
            // initSomeOtherChart(component, "some-other-chart");
            break;

        default:
            // Если компонент не совпадает с известными, просто игнорируем
            break;
    }

});
