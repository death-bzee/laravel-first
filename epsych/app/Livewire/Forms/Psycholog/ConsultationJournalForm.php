<?php

namespace App\Livewire\Forms\Psycholog;

use App\Contracts\Student\ClassroomServiceContract;
use App\Contracts\Student\StudentServiceContract;
use App\Models\Classroom;
use App\Models\ConsultationJournal;
use App\Models\Student;
use App\Repositories\Student\StudentRepository;
use App\Traits\Filament\HasFilamentFields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ConsultationJournalForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasFilamentFields;

    public ?array $data = [];

    public ?ConsultationJournal $record = null;

    public function mount(?ConsultationJournal $record): void
    {
        // Просто присваиваем переданную модель
        $this->record = $record ?? new ConsultationJournal();

        // Загружаем связи, если запись существует
        if ($this->record->exists) {
            $this->record->load(['user', 'student']);
        }

        // Заполняем форму
        $this->form->fill($this->record->exists ? $this->record->toArray() : []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        DatePicker::make('date')
                            ->label(__('Дата'))
                            ->required(),

                        /*Select::make('student_id')
                            ->label(__('Консультируемый'))
                            ->relationship('student', 'full_name')
                            ->options(fn() => app(StudentRepository::class)->getStudents(auth()->user()->organization_id))
                            ->preload()
                            ->searchable()
                            ->default(null),*/

                        MorphToSelect::make('consultable')
                            ->label(__('Консультируемый'))
                            ->types([
                                MorphToSelect\Type::make(Classroom::class)
                                    ->label(__('Класс'))
                                    ->titleAttribute('classroom_full_name')
                                    ->searchColumns(['classroom_full_name'])
                                    ->modifyOptionsQueryUsing(fn() => app(ClassroomServiceContract::class)->getAccessibleClassroomsQuery()),
                                MorphToSelect\Type::make(Student::class)
                                    ->label(__('Ученик'))
                                    ->titleAttribute('full_name')
                                    ->modifyOptionsQueryUsing(fn() => app(StudentServiceContract::class)->getStudentsQuery())
                                    ->getOptionLabelFromRecordUsing(fn (Student $student) =>$student->fullNameClassroom),
                            ])
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),

                        Fieldset::make(__('Запрос'))->schema([
                            $this->getTextarea('request', __('Описание запроса')),
                            $this->getFileUploadComponent('psycholog_request_documents', __('Прилагаемые документы')),
                        ]),

                        $this->getTextarea('recommendations', __('Рекомендации')),
                        $this->getTextarea('notes', __('Примечания')),

                        TextInput::make('consultant')
                            ->label(__('Консультант'))
                            ->required()
                            ->maxLength(255),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record ?: new ConsultationJournal());
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['user_id'] = auth()->user()->id;

        if ($this->record->exists) {
            $this->record->update($data);
        } else {
            $this->record = ConsultationJournal::query()->create($data);
        }

        $this->form->model($this->record)->saveRelationships();

        $this->redirect(route('consultation-journals'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.forms.psycholog.consultation-journal-form');
    }
}
