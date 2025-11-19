const chartInstances = new Map();

export function setupChartInstance(component, chartId) {
    const ctx = document.getElementById(chartId);

    if (!ctx) {
        console.error("❌ Chart context not found:", chartId);
        return null;
    }

    // Удаляем старый график, если он уже есть
    if (chartInstances.has(component.id)) {
        chartInstances.get(component.id).destroy();
        chartInstances.delete(component.id);
    }

    component.cleanup(() => {
        if (chartInstances.has(component.id)) {
            chartInstances.get(component.id).destroy();
            chartInstances.delete(component.id);
        }
    });

    return ctx;
}

// Экспортируем chartInstances, чтобы использовать в других файлах
export { chartInstances };
