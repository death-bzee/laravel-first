import QrScanner from "./QrScanner.js";

const scanners = new Map();

Livewire.hook("component.init", ({component, cleanup}) => {
    if (component.name !== "components.qr.qr-scanner-component") return;

    const controller = new QrScanner(component);
    scanners.set(component.id, controller);

    controller.start().catch(console.error);

    cleanup(() => {
        controller.stop().then(() => scanners.delete(component.id));
    });
});
