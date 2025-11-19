<x-guest-layout>
    <x-layouts.content-container class="max-w-8xl mx-auto md:px-0" uk-height-viewport="offset-top: true; offset-bottom: true">
        <div class="flex flex-col justify-center gap-8">
            <div>
                <x-h1 class="text-center">{{ __('QR Сканер') }}</x-h1>
            </div>
            <div class="flex justify-center">
                <livewire:components.qr.qr-scanner-component />
            </div>
        </div>
    </x-layouts.content-container>
</x-guest-layout>
