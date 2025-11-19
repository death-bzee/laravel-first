<?php

namespace App\Livewire\Forms\SocialPedagogue;

use App\Enums\SocialRoleEnum;
use App\Enums\TypeFormReportEnum;
use App\Models\SocialWorkPlan;
use App\Traits\Filament\HasFilamentFields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class SocialWorkPlanForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasFilamentFields;

    public ?array $data = [];

    public ?SocialWorkPlan $record = null;

    public function mount(?SocialWorkPlan $record = null): void
    {
        $this->record = $record ?? new SocialWorkPlan();
        $this->form->fill($this->record->exists ? $this->record->toArray() : []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextInput::make('event_title')
                            ->label(__('Мероприятие'))
                            ->required(),

                        DatePicker::make('execution_deadline')
                            ->label(__('Срок исполнения'))
                            ->afterOrEqual(today())
                            ->required(),

                        Select::make('type_responsible_person')
                            ->label(__('Ответственные'))
                            ->options(SocialRoleEnum::class),

                        Fieldset::make(__('Форма отчета'))
                            ->schema([
                                Select::make('type_form_report')
                                    ->label(__('Тип отчета'))
                                    ->options(TypeFormReportEnum::class)
                                    ->reactive(), // Чтобы обновлять форму при изменении значения

                                $this->getFileUploadComponent('social_pedagogue_document_form_report', __('Прилагаемые документы'))
                                    ->visible(fn($get) => $get('type_form_report') === TypeFormReportEnum::FileUpload->value),
                            ]),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record ?: new SocialWorkPlan());
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['user_id'] = auth()->user()->id;

        if ($this->record->exists) {
            $this->record->update($data);
        } else {
            $this->record = SocialWorkPlan::query()->create($data);
        }

        $this->form->model($this->record)->saveRelationships();

        $this->redirect(route('social-work-plans'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.forms.social-pedagogue.social-work-plan-form');
    }
}
