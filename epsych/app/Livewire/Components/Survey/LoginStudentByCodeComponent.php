<?php

namespace App\Livewire\Components\Survey;

use App\Contracts\Survey\SurveyGroupAssignmentServiceContract;
use App\Models\Survey\SurveyGroupAssignment;
use Illuminate\View\View;
use Livewire\Component;

class LoginStudentByCodeComponent extends Component
{
    public ?SurveyGroupAssignment $surveyGroupAssignment = null;


    public function sendUniqueCode(string $qrCode): void
    {
        $this->reset(['surveyGroupAssignment']);

        $this->surveyGroupAssignment = app(SurveyGroupAssignmentServiceContract::class)->findByUniqueCode($qrCode);

        session()->put('survey_group_unique_code', $this->surveyGroupAssignment->unique_code);

        $this->redirect(route('student.login-qr-code'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.components.survey.login-student-by-code-component');
    }
}
