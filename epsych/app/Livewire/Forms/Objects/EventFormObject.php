<?php

namespace App\Livewire\Forms\Objects;

use App\Models\Concerns\EventStatus;
use App\Models\Event;
use App\Traits\Classroom\HasClassrooms;
use App\Traits\Document\HasTmpDocuments;
use App\Traits\Student\HasStudents;
use Exception;
use Illuminate\Support\Carbon;
use Livewire\Form;

class EventFormObject extends Form
{
    use HasStudents, HasTmpDocuments, HasClassrooms;

    public ?Event $event = null;
    public ?string $title = null;
    public ?int $classroom_id = null;
    public ?int $event_status_id = null;
    public ?string $event_date = null;
    public array $classRooms = [];

    /**
     * Массив студентов, доступных для выбора в задании.
     *
     * @var array
     */
    public array $students = [];

    /**
     * Идентификатор выбранного студента, если применимо.
     *
     * @var array|null
     */
    public ?array $student_selected = [];

    /**
     * Указывает, находится ли форма в режиме редактирования.
     *
     * @var bool
     */
    public bool $isEdit = false;

    public array $eventStatuses = [];
    public array $tmpDocuments = [];
    public array $storedDocuments = [];

    public function setData(Event $event = null): void
    {
        $this->event = $event;
        $this->title = $event->title ?? '';
        $this->classroom_id = $event->classroom_id ?? null;
        $this->event_status_id = $event->event_status_id ?? null;
        $this->event_date = $event ? Carbon::parse($event->event_date)->format('d/m/Y') : null;

        $this->classRooms = $this->setClassRooms();

        if ($this->classroom_id) {
            $this->students = $this->getStudents();

            if($this->event) {
                $this->student_selected = $this->event->students->pluck('id')->toArray();
            }
        }

        $this->eventStatuses = EventStatus::all()->pluck('title', 'id')->toArray();
    }

    public function setDocuments(Event $event = null): void
    {
        $this->tmpDocuments[5] = $this->tmpDocuments[5] ?? [];
        $this->storedDocuments[5] = $this->storedDocuments[5] ?? [];

        if ($event) {
            $this->storedDocuments[5] = $event->documents()->get()->toArray();
        }
    }

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'event_status_id' => ['nullable', 'exists:event_statuses,id'],
            'event_date' => ['required', 'date'],
            'tmpDocuments.*.*' => ['required', 'mimes:jpg,jpeg,png,gif,docx,pdf', 'max:1500000'],
        ];
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        $this->validate();

        if ($this->event) {
            $this->event->update([
                'title' => $this->title,
                'classroom_id' => $this->classroom_id,
                'event_status_id' => $this->event_status_id,
                'event_date' => Carbon::createFromFormat('d.m.Y', $this->event_date)->format('Y-m-d'),
            ]);
        } else {
            $this->event = Event::create([
                'title' => $this->title,
                'classroom_id' => $this->classroom_id,
                'event_status_id' => $this->event_status_id,
                'event_date' => Carbon::createFromFormat('d.m.Y', $this->event_date)->format('Y-m-d'),
                'organization_id' => auth()->user()->organization_id,
            ]);
        }

        $this->tmpDocuments = $this->saveTmpDocuments($this->tmpDocuments, 5, $this->event->id, $this->event);

        // Обработка связей студентов
        if ($this->event && isset($this->student_selected)) {
            $this->updateStudentAssociations($this->event, $this->student_selected);
        }
    }

    public function getStudents(): array
    {
        $studentsCollection = $this->getStudentsByClassroomAndOrganization($this->classroom_id);
        return $this->mapStudents($studentsCollection);
    }
}
