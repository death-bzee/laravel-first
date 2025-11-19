<?php

namespace App\Actions\Qr;

use App\Contracts\Qr\QrCodeActionContract;
use App\Models\Survey\SurveyGroupAssignment;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class LoginToSurveyAction implements QrCodeActionContract
{
    public function __construct(protected Component $component)
    {
    }

    public function handle(Model $model): void
    {
        /** @var SurveyGroupAssignment $model */
        $this->component->reset(['surveyGroupAssignment']);
        $this->component->surveyGroupAssignment = $model;

        session()->put('survey_group_id', $model->id);

        $this->component->redirect(route('student.login-qr-code'), navigate: true);
    }
}
