<x-app-layout>
    <x-layouts.content-container>
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div>
                <x-h1 class="mb-6">{{ __('Ученики') }}</x-h1>
                <div>{!! __('Консультативное направление позволяет  профессионально помогать как индивидуальному учащемуся или группе учащихся в решении их проблемной ситуации.') !!} <x-link href="#" :navigate="false" uk-toggle="target: #students-reference">{{ __('Справка') }}</x-link></div>
            </div>
            <x-button-link href="{{ route('student-create') }}">{{ __('Добавить ученика') }}</x-button-link>
        </div>
        <div>
            <livewire:tables.student.student-table />
        </div>
    </x-layouts.content-container>
</x-app-layout>

<x-uikit.uk-modal
    id="students-reference"
    title="{{ __('Сведения об учащихся школы ') }}"
    content="{{ __('students-reference') }}"
/>
