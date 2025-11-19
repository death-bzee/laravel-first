<?php

namespace App\Livewire\Forms\Bullying;

use App\Enums\Bullying\PreventionEventStatusEnum;
use App\Enums\Bullying\PreventionEventTypeEnum;
use App\Enums\SocialRoleEnum;
use App\Models\Bullying\PreventionEvent;
use App\Traits\Filament\HasFilamentFields;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class PreventionEventForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasFilamentFields;

    public ?array $data = [];

    public PreventionEvent $record;

    public function mount(?PreventionEvent $record = null): void
    {
        $this->record = $record ?? new PreventionEvent();

        $this->form->fill($this->record->exists ? $this->record->toArray() : []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('Название мероприятия'))
                    ->required()
                    ->columnSpanFull(),

                Select::make('responsible')
                    ->label(__('Ответственный'))
                    ->required()
                    ->options(SocialRoleEnum::class)
                    ->native(false),

                Select::make('type')
                    ->label(__('Тип мероприятия'))
                    ->required()
                    ->options(PreventionEventTypeEnum::class)
                    ->native(false),

                Select::make('status')
                    ->label(__('Статус'))
                    ->required()
                    ->options(PreventionEventStatusEnum::class)
                    ->native(false),

                DatePicker::make('date')
                    ->label(__('Дата'))
                    ->required(),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['organization_id'] = auth()->user()->organization_id;

        if ($this->record->exists) {
            $this->record->update($data);
        } else {
            $this->record = PreventionEvent::query()->create($data);
        }

        $this->form->model($this->record)->saveRelationships();

        $this->redirect(route('bullying-prevention-events'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.forms.bullying.prevention-event-form');
    }
}
