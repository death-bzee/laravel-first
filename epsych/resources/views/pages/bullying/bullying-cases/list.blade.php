<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Зарегистрированные случаи буллинга') }}</x-h1>
            </div>
            @if(auth()->user()->organization_id)
                <livewire:components.qr.download-qr-code-component
                    :model="\App\Models\Organization::find(auth()->user()->organization_id)"
                    :uri="'/bullying/report/'" />
            @endif
        </div>
        <div>
            <livewire:tables.bullying.bullying-case-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>
