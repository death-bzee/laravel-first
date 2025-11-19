<?php

namespace App\Livewire\Forms\Survey;

use App\Livewire\Forms\Objects\SurveyFormObject;
use Exception;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class SurveyForm extends Component
{
    public SurveyFormObject $form;

    #[On('survey-complete')]
    public function mount(): void
    {
        $this->form->setData();
    }

    /**
     * @throws Exception
     */
    public function nextQuestion(): void
    {
        $this->form->nextQuestion();
    }

    public function previousQuestion(): void
    {
        $this->form->previousQuestion();
    }

    public function render(): View
    {
        return view('livewire.forms.survey.survey-form');
    }
}
