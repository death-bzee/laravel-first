<?php

namespace App\Livewire\Forms;

use App\Livewire\Forms\Objects\EventFormObject;
use App\Models\Event;
use App\Traits\Document\HasStoredDocuments;
use App\Traits\Document\HasTmpDocuments;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventForm extends Component
{
    use WithFileUploads, HasTmpDocuments, HasStoredDocuments;

    public EventFormObject $form;

    public function mount($eventId = null): void
    {
        if ($eventId) {
            $event = Event::findOrFail($eventId);
        } else {
            $event = null;
        }
        $this->form->setData($event);
        $this->form->setDocuments($event);
    }

    public function updated(): void
    {
        $this->validate([
            'form.tmpDocuments.*.*' => 'mimes:jpg,jpeg,png,gif,docx,pdf|max:1500000',
        ]);
    }

    public function updatedFormClassroomId(): void
    {
        $this->form->student_selected = [];
        $this->form->students = [];
        $this->form->students = $this->form->getStudents();
        if($this->form->event) {
            $this->form->student_selected = $this->form->event->students->pluck('id')->toArray();
        }
    }

    public function save(): void
    {
        try {
            if ($this->form->event) {
                $this->authorize('update', $this->form->event);
            } else {
                $this->authorize('create', Event::class);
            }

            $this->form->save();
            $this->redirect('/events', navigate: true);
        } catch (AuthorizationException $e) {
            abort(403, __('Доступ запрещен'));
        }
    }

    public function render()
    {
        return view('livewire.forms.event-form');
    }
}
