<div>
    <div id="qr-reader" class="mx-auto mb-6" style="width: 800px"></div>

    <livewire:components.qr.qr-controller-component />
</div>

@push('scripts')
    @vite(['resources/js/qr-scanner/index.js'])
@endpush
