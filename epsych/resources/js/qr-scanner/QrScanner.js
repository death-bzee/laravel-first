import {Html5Qrcode} from "html5-qrcode";

export default class QrScanner {
    constructor(component, elementId = "qr-reader") {
        this.component = component;
        this.elementId = elementId;
        this.scanner = new Html5Qrcode(this.elementId);
        this.isActive = false;
    }

    async start() {
        if (this.isActive) return;
        this.isActive = true;

        try {
            await this.scanner.start(
                {facingMode: "environment"},
                {fps: 20, qrbox: 500},
                (qrCode) => this.handleScan(qrCode),
                (error) => this.handleScanError(error)
            );
        } catch (error) {
            console.error("❌ Не удалось запустить сканер:", error);
        }
    }

    async stop() {
        if (!this.isActive) return;
        this.isActive = false;

        try {
            await this.scanner.stop();
            this.scanner.clear();
        } catch (error) {
            console.error("❌ Ошибка при остановке сканера:", error);
        }
    }

    async handleScan(qrCode) {
        try {
            await this.stop();
            console.log("📷 Камера остановлена");

            await this.component.$wire.dispatch('qr-code-scanned', {uuidQrCode: qrCode});

            setTimeout(() => this.start(), 3000);

        } catch (error) {
            console.error("❌ Ошибка при обработке QR-кода:", error);
        }
    }

    handleScanError(error) {
        console.warn("⚠️ QR scanning warning:", error);
    }
}
