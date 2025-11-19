<?php

namespace App\Livewire\Forms\Bullying;

use App\Enums\RoleEnum;
use App\Models\Bullying\BullyingCase;
use App\Models\Organization;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class BullyingCaseForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public BullyingCase $record;

    public $organizationId;

	public $phone;

    public function mount(): void
    {
        if (! is_numeric($this->organizationId)) {
            abort(404);
        }

        $this->record = new BullyingCase;

		$organization = Organization::query()
			->with('district')
			->find($this->organizationId);

    	$this->phone = $organization->district?->phone;

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('victim')
                    ->label(__('Потерпевший'))
                    ->maxLength(255)
                    ->placeholder(__('ФИО Класс Возраст'))
                    ->required(),

                TextInput::make('aggressor')
                    ->label(__('Агрессор'))
                    ->placeholder(__('ФИО Класс'))
                    ->maxLength(255),

                Textarea::make('description')
                    ->label(__('Описание инцидента'))
                    ->maxLength(3000)
                    ->rows(5)
                    ->required(),

                DatePicker::make('incident_date')
                    ->label(__('Дата происшествия'))
                    ->required(),

                Select::make('selected_role')
                    ->label(__('Кому отправить'))
                    ->options([
                        RoleEnum::StudentAffairsManager->value => RoleEnum::StudentAffairsManager->label(),
                        RoleEnum::CorrectionalServiceRegion->value => RoleEnum::CorrectionalServiceRegion->label(),
                    ])
                    ->required()
                    ->columnSpanFull(),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $data['organization_id'] = (int) $this->organizationId;

        $data['role_id'] = Role::query()
            ->where('name', $data['selected_role'])
            ->value('id');

        $this->record = BullyingCase::query()->create($data);

        $this->form->model($this->record)->saveRelationships();

        $this->redirect(route('bullying-report-sent', ['organizationId' => $this->organizationId]), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.forms.bullying.bullying-case-form');
    }
}
