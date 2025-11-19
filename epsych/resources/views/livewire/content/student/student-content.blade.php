<div class="flex flex-col gap-6 md:gap-10">
    @if($record)
        <livewire:infolists.student-infolist :$record />
    @endif
    @if($hasDiagnosis)
        <div>
            <x-h1 class="mt-4">{{ __('Результаты тестирования') }}</x-h1>
            <livewire:tables.survey.survey-student-diagnosis-table studentId="{{ $student->id }}" />
        </div>
    @endif
    @if($hasConsultJournal)
        <x-h1 class="mt-4">{{ __('Журнал консультаций') }}</x-h1>
        <livewire:tables.student.consultation-journal-table studentId="{{ $student->id }}" />
    @endif
</div>
