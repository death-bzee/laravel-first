<?php

namespace App\Livewire\Forms\Psycholog;

use App\Enums\EventTypeEnum;
use App\Enums\ResponsiblePersonEnum;
use App\Models\WorkPlan;
use App\Traits\Filament\HasFilamentFields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class WorkPlanForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasFilamentFields;

    public ?array $data = [];

    public ?WorkPlan $record = null;

    public function mount(?WorkPlan $record = null): void
    {
        // Просто присваиваем переданную модель
        $this->record = $record ?? new WorkPlan();


        if ($this->record->exists) {
            // Принудительно преобразуем `work_planable_type` в `event_type`
            $this->record->event_type = EventTypeEnum::tryFromModel($this->record->work_planable_type);

            // Наполняем форму
            $this->form->fill($this->record->toArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('item_number')
                            ->label(__('№ п/п'))
                            ->numeric()
                            ->required(),

                        Select::make('event_type')
                            ->label(__('Мероприятие'))
                            ->options(EventTypeEnum::class)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $modelClass = EventTypeEnum::from($state)?->getModelClass();
                                $set('work_planable_type', $modelClass);
                            }),
                        Hidden::make('work_planable_type')
                            ->dehydrated(), // Убедимся, что поле сохраняется

                        Select::make('activity_direction_id')
                            ->label(__('Направление деятельности'))
                            ->relationship('activityDirection', 'title')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('activity_direction_other', $state === 6 ? '' : null)),

                        TextInput::make('activity_direction_other')
                            ->label(__('Другое направление деятельности'))
                            ->visible(fn($get) => (int)$get('activity_direction_id') === 6)
                            ->required(fn($get) => (int)$get('activity_direction_id') === 6),

                        /*TextInput::make('target_group')
                            ->label(__('Целевая группа')), // Проверяем, что вообще приходит*/

                        Select::make('target_group_id')
                            ->label(__('Целевая группа'))
                            ->relationship('targetGroup', 'title'),

                        DatePicker::make('execution_deadline')
                            ->label(__('Срок исполнения'))
                            ->afterOrEqual(today())
                            ->required(),

                        Fieldset::make(__('Форма завершения'))
                            ->schema([
                                $this->getTextarea('completion_form', __('Текст'))->required(),
                                $this->getFileUploadComponent('psycholog_completion_form', __('Прилагаемые документы')),
                            ]),

                        Select::make('responsible_person')
                            ->label(__('Ответственные'))
                            ->options(ResponsiblePersonEnum::class)
                            ->required(),

                        /*Fieldset::make(__('Пометка об исполнении'))
                            ->schema([
                                MorphToSelect::make('workPlanable')
                                    ->label(__('Мероприятие'))
                                    ->types([
                                        MorphToSelect\Type::make(SurveyGroupAssignment::class)
                                            ->label(__('Тестирование'))
                                            ->titleAttribute('title')
                                            ->modifyOptionsQueryUsing(fn($query) => $query->where('organization_id', auth()->user()->organization_id)),
                                        MorphToSelect\Type::make(ConsultationJournal::class)
                                            ->label(__('Журнал консультаций'))
                                            ->getOptionLabelFromRecordUsing(fn(ConsultationJournal $record) => "({$record->date}) {$record->student?->fullName}")
                                            ->modifyOptionsQueryUsing(fn($query) => $query->where('user_id', auth()->id())),
                                    ])
                                    ->columnSpanFull(),
                                $this->getTextarea('execution_note', __('Описание')),
                                $this->getFileUploadComponent('psycholog_execution_note_form', __('Прилагаемые документы'))
                            ])*/
                    ]),
            ])
            ->statePath('data')
            ->model($this->record ?: new WorkPlan());
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['user_id'] = auth()->user()->id;

        if ($this->record->exists) {
            $this->record->update($data);
        } else {
            $this->record = WorkPlan::query()->create($data);
        }

        $this->form->model($this->record)->saveRelationships();

        $this->redirect(route('work-plans'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.forms.psycholog.work-plan-form');
    }
}
