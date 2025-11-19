<?php

namespace App\Livewire\Forms;

use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use App\Models\Classroom;
use App\Models\SocialPassport;
use App\Models\Student;
use App\Traits\Filament\HasFilamentFields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class StudentForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasFilamentFields;

    public ?array $data = [];

    public ?Student $record = null;

    public function mount(?Student $record = null): void
    {
        $this->record = $record ?? new Student();
        $this->form->fill($this->record->exists ? $this->record->toArray() : []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('surname')
                            ->label(__('Фамилия'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label(__('Имя'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('patronymic')
                            ->label(__('Отчество'))
                            ->maxLength(255)
                            ->default(null),
                    ]),
                Grid::make(3)
                    ->schema([
                        Select::make('gender')
                            ->label(__('Пол'))
                            ->options(GenderEnum::class),
                        DatePicker::make('birthday')
                            ->label(__('Дата рождения'))
                            ->required(),
                        Select::make('classroom_id')
                            ->label(__('Класс'))
                            ->searchPrompt(__('Вводите класс и литеру для поиска'))
                            ->hint(__('Вводите класс и литеру для поиска'))
                            ->hintColor('danger')
                            ->options(Classroom::all()->pluck('classroomName', 'id'))
                            ->searchable(['grade', 'letter'])
                            ->preload()
                            ->required()
                            ->hidden(RoleEnum::requiresClasroomContains(auth()->user()->getRoleNames()->first()))
                            ->placeholder(__('Выберите класс')),
                    ]),
                Grid::make(3)
                    ->schema([
                        Select::make('nationality_id')
                            ->label(__('Национальность'))
                            ->relationship('nationality', 'title', function ($query) {
                                $query->orderBy('sort');
                            })
                            ->preload()
                            ->searchable(),
                    ]),
                Fieldset::make(__('Родитель / Законный представитель'))
                    ->relationship('parent')
                    ->schema([
                        TextInput::make('surname')
                            ->label(__('Фамилия'))
                            ->required(),
                        TextInput::make('name')
                            ->label(__('Имя'))
                            ->required(),
                        TextInput::make('patronymic')
                            ->label(__('Отчество (при наличии)')),
                        Select::make('education_level_id')
                            ->label(__('Образование'))
                            ->relationship('educationLevel', 'title')
                            ->required(),
                        TextInput::make('job')
                            ->label(__('Место работы'))
                            ->required(),
                        TextInput::make('address')
                            ->label(__('Адрес'))
                            ->required(),
                        TextInput::make('phone')
                            ->label(__('Телефон'))
                            ->tel()
                            ->mask('+9 999 999-99-99')
                            ->required(),
                    ])->columns(3),
                Section::make(__('Социальный статус'))
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Repeater::make('studentSocialPassports')
                                    ->label(__('Заполните социальные статусы'))
                                    ->relationship('studentSocialPassports')
                                    ->schema([
                                        Select::make('social_passport_id')
                                            ->label(__('Социальный статус'))
                                            ->options(SocialPassport::query()->pluck('title', 'id'))
                                            ->required()
                                            ->preload()
                                            ->searchable()
                                            ->reactive()
                                            ->afterStateUpdated(fn(callable $set) => $set('value', true)), // Принудительно обновляет value при изменении

                                        Toggle::make('value')
                                            ->label(__('Да'))
                                            ->default(true)
                                            ->visible(fn($get) => filled($get('social_passport_id'))), // Проверка, что значение выбрано
                                    ])
                                    ->collapsible()
                                    ->itemLabel(fn(array $state): ?string => SocialPassport::query()->find($state['social_passport_id'])?->title ?? __('Новый статус'))
                                    ->defaultItems(0)
                                    ->columnSpanFull()
                                    ->createItemButtonLabel(__('Добавить социальный статус'))
                            ]),
                    ]),
                Grid::make(3)
                    ->schema([
                        TextInput::make('family_size')
                            ->label(__('Состав семьи (кол-во)'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(20)
                            ->default(1)
                            ->required(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record ?: Student::class);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['organization_id'] = auth()->user()->organization_id;

        if ($this->record->exists) {
            $this->record->update($data);
        } else {
            if (RoleEnum::requiresClasroomContains(auth()->user()->getRoleNames()->first())) {
                $data['classroom_id'] = auth()->user()->classrooms()->value('classrooms.id');
            }

            $this->record = Student::query()->create($data);
        }

        $this->form->model($this->record)->saveRelationships();

        $this->redirect(route('students'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.forms.student-form');
    }

}
