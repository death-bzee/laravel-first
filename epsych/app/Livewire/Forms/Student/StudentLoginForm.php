<?php

namespace App\Livewire\Forms\Student;

use App\Models\Concerns\Relation\AccessTokenStudentSurvey;
use App\Models\Survey\SurveyAssignment;
use Illuminate\View\View;
use Livewire\Component;

class StudentLoginForm extends Component
{
    public ?string $access_code = null;
    public ?AccessTokenStudentSurvey $token = null;

    public function login(): void
    {
        $cleanedAccessCode = str_replace('-', '', $this->access_code);

        $this->validate([
            'access_code' => 'required|string',
        ]);

        $this->token = AccessTokenStudentSurvey::where('access_code', $cleanedAccessCode)
            ->with('accessToken') // Предварительно загружаем связь
            ->first();

        if ($this->token && $this->token->accessToken) {

            // Сохранение токена в сессии
            session()->put('access_token', $this->token->accessToken->token);
            session()->put('survey_assignment_id', $this->token->survey_assignment_id);
            session()->put('student_id', $this->token->student_id);

            // Установка времени начала прохождения опроса
            SurveyAssignment::where('id', $this->token->survey_assignment_id)
                ->update(['started_at' => now()]);

            // Перенаправление на страницу опроса
            $this->redirect('/survey', navigate: true);
        }

    }
    public function render(): View
    {
        return view('livewire.forms.student.student-login-form');
    }
}
