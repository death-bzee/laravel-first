<?php

namespace App\Livewire\Forms\Survey;

use App\Contracts\Student\ClassroomServiceContract;
use App\Enums\Survey\SurveyGroupAssignmentTypeEnum;
use App\Models\Student;
use App\Models\Survey\SurveyGroupAssignment;
use App\Repositories\Survey\SurveyAssignmentRepository;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class SurveyGroupAssignmentForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public ?SurveyGroupAssignment $surveyGroupAssignment = null;

    public function mount(?int $surveyGroupAssignmentId = null): void
    {
        $this->surveyGroupAssignment = $surveyGroupAssignmentId ? SurveyGroupAssignment::query()->find($surveyGroupAssignmentId) : new SurveyGroupAssignment();

        if ($this->surveyGroupAssignment->exists) {
            $this->form->fill($this->surveyGroupAssignment->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->label(__('Заголовок'))
                            ->maxLength(255),

                        Select::make('survey_id')
                            ->label(__('Тест'))
                            ->relationship('survey', 'title')
                            ->preload()
                            ->searchable()
                            ->required(),

                        Select::make('type')
                            ->label(__('Тип теста'))
                            ->options(SurveyGroupAssignmentTypeEnum::class)
                            ->live()
                            ->required(),

                        Select::make('classroom_id')
                            ->label(__('Класс'))
                            ->options(fn() => app(ClassroomServiceContract::class)->getAccessibleClassrooms())
                            ->searchable(['grade', 'letter'])
                            ->preload()
                            ->required()
                            ->placeholder(__('Выберите класс'))
                            ->live()
                            ->afterStateUpdated(fn(callable $set, Get $get) => !$get('record') ? $set('students', []) : null),

                        Select::make('students')
                            ->label(__('Ученики'))
                            ->relationship(
                                name: 'students',
                                modifyQueryUsing: fn(Builder $query, Get $get) => $get('type') === SurveyGroupAssignmentTypeEnum::Individual->value && $get('classroom_id')
                                    ? $query->where('classroom_id', $get('classroom_id'))
                                    : $query
                            )
                            ->getOptionLabelFromRecordUsing(fn(Student $student) => "{$student->surname} {$student->name} " . ($student->patronymic ?? ''))
                            ->searchable(['surname', 'name', 'patronymic'])
                            ->preload()
                            ->multiple()
                            ->required()
                            ->hidden(fn(Get $get) => !($get('type') === SurveyGroupAssignmentTypeEnum::Individual->value && $get('classroom_id'))),
                    ]),
            ])
            ->statePath('data')
            ->model($this->surveyGroupAssignment ?: SurveyGroupAssignment::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['organization_id'] = auth()->user()->organization_id;

        if ($this->surveyGroupAssignment->exists) {
            $this->surveyGroupAssignment->update($data);
        } else {
            $this->surveyGroupAssignment = SurveyGroupAssignment::query()->create($data);
        }

        // Сохраняем отношения формы
        $this->form->model($this->surveyGroupAssignment)->saveRelationships();

        // Обновляем назначения учеников через репозиторий
        app(SurveyAssignmentRepository::class)->updateAssignments(
            $this->surveyGroupAssignment->id,
            $data['students'] ?? []
        );

        $this->redirect(route('survey-group-assign'), navigate: true);
    }


    public function render(): View
    {
        return view('livewire.forms.survey.survey-group-assignment-form');
    }
}
