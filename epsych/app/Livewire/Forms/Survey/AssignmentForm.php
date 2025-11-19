<?php

namespace App\Livewire\Forms\Survey;

use App\Livewire\Forms\Objects\SurveyAssignmentFormObject;
use App\Models\Survey\SurveyAssignment;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Component;

class AssignmentForm extends Component
{
    public SurveyAssignmentFormObject $form;

    public function mount($surveyAssignmentId = null): void
    {
        if ($surveyAssignmentId) {
            $surveyAssignment = SurveyAssignment::findOrFail($surveyAssignmentId);
            $this->form->isEdit = true;
        } else {
            $surveyAssignment = null;
        }
        $this->form->setData($surveyAssignment);
    }

    public function updatedFormGroupId($value): void
	{
        $this->form->students = $this->form->setStudents($value);
	}

    public function save(): void
    {
        try {
            if ($this->form->surveyAssignment) {
                $this->authorize('update', $this->form->surveyAssignment);
            } else {
                $this->authorize('create', SurveyAssignment::class);
            }

            $this->form->save();
            $this->redirect('/survey-assign', navigate: true);
        } catch (AuthorizationException $e) {
            abort(403, __('Доступ запрещен'));
        }
    }

    public function render()
    {
        return view('livewire.forms.survey.assignment-form');
    }
}
