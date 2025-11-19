<?php

namespace App\Livewire\Forms\Objects;

use App\Actions\Survey\CompleteSurveyGroupAction;
use App\Contracts\Survey\SurveyServiceContract;
use App\Enums\Survey\SurveyQuestionTypeEnum;
use App\Jobs\Survey\ProcessSurveyResultsJob;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyQuestion;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Form;

class SurveyFormObject extends Form
{
    public Survey $survey;

    public ?SurveyAssignment $surveyAssignment;

    public ?string $surveyTitle = null;

    public ?int $questionCount = null;

    public ?int $currentQuestionNumber = null;

    public ?SurveyQuestion $currentQuestion = null;

    public ?Collection $currentQuestionOptions = null;

    public ?array $questionOptionSelectedIds = [];

    public ?array $questionOptionStudentSelectedIds = [];

    public ?int $questionOptionSelectedId = null;

    public ?string $questionOptionTypeInput = null;

    public ?array $questionAnswer = [];

    public array $studentData = [];

    public bool $isSurveyComplete = false;

    public bool $isSurveyStarted = false;

    protected ?SurveyServiceContract $surveyService = null;

    // Метод для ленивой инициализации сервиса
    protected function getSurveyService(): SurveyServiceContract
    {
        if (is_null($this->surveyService)) {
            $this->surveyService = app(SurveyServiceContract::class);
        }

        return $this->surveyService;
    }

    public function setData(): void
    {
        $surveyService = $this->getSurveyService();

        if (! isset($survey_assignment_id)) {
            $survey_assignment_id = session('survey_assignment_id');
        } else {
            return;
        }

        $this->surveyAssignment = $surveyService->getSurveyAssignment($survey_assignment_id);

        if ($this->surveyAssignment) {

            if ($this->surveyAssignment->group->started_at) {
                $this->isSurveyStarted = true;
            }

            if ($this->surveyAssignment->completed_at) {
                $this->isSurveyComplete = true;
            }

            $this->survey = Survey::with('questions')->find($this->surveyAssignment->group->survey_id);

            $this->surveyTitle = $this->survey->title;
            $this->studentData = $this->getStudentData();
            $this->questionCount = $this->survey->questions->count();

            if (is_null($this->currentQuestionNumber) && is_null($this->surveyAssignment->completed_at)) {
                $this->currentQuestionNumber = $surveyService->getNextNumberQuestion($this->surveyAssignment);
                $this->getCurrentQuestion($this->currentQuestionNumber);
            }
        } else {
            Session::flush();
            redirect()->route('student-login');
        }
    }

    /**
     * @throws Exception
     */
    public function nextQuestion(): void
    {
        $this->validate();

        $surveyService = $this->getSurveyService();

        if ($this->currentQuestion) {

            $surveyService->setAnswer($this->getQuestionOptionAnswerData());

            if ($this->currentQuestionNumber === $this->questionCount) {
                $surveyService->setSurveyComplete($this->surveyAssignment);
                $this->isSurveyComplete = true;

                // Устанавливаем статус Completed для SurveyGroupAssignment
                app(CompleteSurveyGroupAction::class)->handle($this->surveyAssignment->group->id);

                // Отправляем цепочку задач для вычисления результатов опроса (диагноз, шкалирование, интерпретация)
                ProcessSurveyResultsJob::dispatch($this->surveyAssignment->id);
            }

            if ($this->currentQuestionNumber < $this->questionCount) {
                $this->currentQuestionNumber++;
                $this->getCurrentQuestion($this->currentQuestionNumber);
            }
        }

        $this->reset([
            'questionOptionSelectedId', 'questionOptionSelectedIds', 'questionOptionStudentSelectedId',
        ]);
    }

    public function previousQuestion(): void
    {
        if ($this->currentQuestionNumber > 1) {
            $this->currentQuestionNumber--;
            $this->getCurrentQuestion($this->currentQuestionNumber);
        } else {
            logger()->info('Already at the first question, cannot go back further.');
        }
    }

    protected function rules(): array
    {
        $rules = [];

        switch ($this->currentQuestion->type) {
            case SurveyQuestionTypeEnum::SingleChoice:
                $rules['questionOptionSelectedId'] = ['required', 'int'];
                break;

            case SurveyQuestionTypeEnum::DropdownChoice:
            case SurveyQuestionTypeEnum::MultipleChoice:
            case SurveyQuestionTypeEnum::LimitedMultipleChoice:
                $rules['questionOptionSelectedIds'] = ['required', 'array'];
                break;
        }

        return $rules;
    }

    protected function getStudentData(): array
    {
        return $this->studentData = [
            'grade' => $this->surveyAssignment->group->classroom->grade,
            'letter' => $this->surveyAssignment->group->classroom->letter,
            'surname' => $this->surveyAssignment->student->surname,
            'name' => $this->surveyAssignment->student->name,
            'patronymic' => $this->surveyAssignment->student->patronymic,
        ];
    }

    protected function getQuestionOptionAnswerData(): array
    {
        $option_ids = [];
        $student_ids = [];

        if ($this->currentQuestion->type === SurveyQuestionTypeEnum::SingleChoice) {
            $option_ids = [$this->questionOptionSelectedId];
        } elseif ($this->currentQuestion->type === SurveyQuestionTypeEnum::MultipleChoice) {
            $option_ids = $this->questionOptionSelectedIds;
        }
        if ($this->currentQuestion->type === SurveyQuestionTypeEnum::DropdownChoice) {
            $student_ids = $this->questionOptionSelectedIds;
        }

        return $this->questionAnswer = [
            'survey_assignment_id' => $this->surveyAssignment->id,
            'question_id' => $this->currentQuestion->id,
            'option_ids' => $option_ids,
            'student_ids' => $student_ids,
        ];
    }

    public function getCurrentQuestion($currentQuestionNumber): void
    {
        $this->currentQuestion = $this->survey->questions->firstWhere('number', $currentQuestionNumber);

        if ($this->currentQuestion) {
            $this->currentQuestionOptions = $this->currentQuestion->options;
            $this->questionOptionTypeInput = $this->currentQuestion->type->inputType();
        }
    }
}
