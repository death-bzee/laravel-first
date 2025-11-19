<?php

namespace App\Livewire\Forms;

use App\Models\Decree;
use App\Traits\Filament\HasFilamentFields;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class DecreForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasFilamentFields;

    public ?array $data = [];

    public Decree $record;

    public function mount(?Decree $record = null): void
    {
        $this->record = $record ?? new Decree();

        $this->form->fill($this->record->exists ? $this->record->toArray() : []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('Приказ'))
                    ->required()
                    ->maxLength(255),
                $this->getFileUploadComponent('decree_form', __('Прилагаемые документы'))
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['user_id'] = auth()->user()->id;

        if ($this->record->exists) {
            $this->record->update($data);
        } else {
            $this->record = Decree::query()->create($data);
        }

        $this->form->model($this->record)->saveRelationships();

        $this->redirect(route('decrees'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.forms.decre-form');
    }
}
