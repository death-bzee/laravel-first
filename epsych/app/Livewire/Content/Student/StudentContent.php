<?php

namespace App\Livewire\Content\Student;

use App\Contracts\Student\StudentPdfServiceContract;
use App\Models\Student;
use Illuminate\View\View;
use Livewire\Component;

class StudentContent extends Component
{
    public ?Student $record = null;
    public bool $hasDiagnosis = false;
    public bool $hasConsultJournal = false;

    public function mount(?Student $record): void
    {
        $this->record = $record;

        $this->hasDiagnosis = $this->record->assignments->some(
            fn($assignment) => $assignment->studentDiagnosis()->exists()
        );

        $this->hasConsultJournal = $this->record
            ->consultationJournals()
            ->exists();
    }

    public function render(): View
    {
        return view('livewire.content.student.student-content', [
            'student' => $this->record,
            'hasDiagnosis' => $this->hasDiagnosis,
            'hasConsultJournal' => $this->hasConsultJournal,
        ]);
    }
}
