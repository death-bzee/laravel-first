<?php

namespace App\Livewire\Components;

use App\Actions\Survey\CreateSurveyTokenAction;
use App\Models\Student;
use App\Models\Survey\SurveyGroupAssignment;
use Exception;
use Livewire\Component;

class LoginStudentQrCodeComponent extends Component
{
    public ?string $survey_group_id = null;

    public array $students = [];

    public ?int $student_selected_id = null;

    public ?SurveyGroupAssignment $surveyGroupAssignment = null;

    public function mount(): void
    {
        $this->survey_group_id = session('survey_group_id');

        if (empty($this->survey_group_id)) {
            abort(403);
        }

        $this->surveyGroupAssignment = SurveyGroupAssignment::query()
            ->where('id', $this->survey_group_id)
            ->firstOrFail();

        $this->students = $this->surveyGroupAssignment
            ->students()
            ->with('classroom')
            ->get()
            ->mapWithKeys(fn($student) => [
                $student->id => $student->fullNameClassroom,
            ])
            ->toArray();
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        $student = Student::query()->findOrFail($this->student_selected_id);

        $surveyAssignment = $this->surveyGroupAssignment
            ->assignments()
            ->where('student_id', $student->id)
            ->firstOrFail();

        $accessTokenSurvey = app(CreateSurveyTokenAction::class)
            ->handle($surveyAssignment, $student);

        if ($accessTokenSurvey && $accessTokenSurvey->exists) {
            session()->put('access_token', $accessTokenSurvey->accessToken->token);
            session()->put('survey_assignment_id', $surveyAssignment->id);
            session()->put('student_id', $student->id);

            $this->redirect(route('survey'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.components.login-student-qr-code-component', [
            'students' => $this->students,
            'student_selected_id' => $this->student_selected_id,
        ]);
    }
}
