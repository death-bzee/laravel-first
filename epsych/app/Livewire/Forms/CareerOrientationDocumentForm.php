<?php

namespace App\Livewire\Forms;

use App\Models\CareerOrientationDocument;
use App\Traits\Filament\HasFilamentFields;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class CareerOrientationDocumentForm extends Component implements HasForms
{
    use InteractsWithForms;
    use HasFilamentFields;

    public ?array $data = [];

    public CareerOrientationDocument $record;

    public function mount(?CareerOrientationDocument $record = null): void
    {
        $this->record = $record ?? new CareerOrientationDocument();

        $this->form->fill($this->record->exists ? $this->record->toArray() : []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('Название документа'))
                    ->required()
                    ->columnSpanFull(),
                $this->getFileUploadComponent('сareer_orientation_document', __('Прилагаемые документы'))
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
            $this->record = CareerOrientationDocument::query()->create($data);
        }

        $this->form->model($this->record)->saveRelationships();

        $this->redirect(route('career-orientation-documents'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.forms.career-orientation-document-form');
    }
}
