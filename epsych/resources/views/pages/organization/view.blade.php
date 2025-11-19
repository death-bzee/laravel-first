<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Социальный паспорт школы') }}</x-h1>
                <div><x-link href="#" :navigate="false" uk-toggle="target: #social-passport-reference">{{ __('Справка') }}</x-link></div>
            </div>
        </div>
        <div>
            <livewire:content.organization.social-passport-school-content :organizationId="auth()->user()->organization_id"/>
        </div>
    </x-layouts.content-container>
</x-app-layout>

<x-uikit.uk-modal
    id="social-passport-reference"
    title="{{ __('Социальный паспорт школы') }}"
    content="{{ __('social-passport-reference') }}"
/>
