<?php

namespace App\Services\Survey;

use App\Enums\Survey\SurveyQuestionTypeEnum;
use App\Repositories\Survey\SurveyAssignmentRepository;

class SurveyResultService
{
    public function __construct(
        protected SurveyAssignmentRepository $surveyAssignmentRepository,
    ) {}

    public function getSurveyResultDiagnosisJson(int $surveyAssignmentId): array
    {
        $surveyAssignment = $this->surveyAssignmentRepository->getSurveyAssignment($surveyAssignmentId);
        $survey = $surveyAssignment->group->survey;

        $json = $this->buildBaseJson($survey);
        $json['interpretation'] = $this->buildInterpretationJson($survey);
        $json['questions'] = $this->buildQuestionsJson($survey, $surveyAssignmentId);

        return $json;
    }

    public function getSurveyResultScalingJson(int $surveyAssignmentId): array
    {
        $surveyAssignment = $this->surveyAssignmentRepository->getSurveyAssignment($surveyAssignmentId);
        $survey = $surveyAssignment->group->survey;

        $json = $this->buildBaseJson($survey);
        $json['interpretation'] = $this->buildInterpretationJson($survey);
        $json['scaling_prompt'] = $survey->scaling_prompt;
        $json['questions'] = $this->buildQuestionsJson($survey, $surveyAssignmentId);

        return $json;
    }

    public function getSurveyResultInterpretationJson(int $surveyAssignmentId): array
    {
        $surveyAssignment = $this->surveyAssignmentRepository->getSurveyAssignment($surveyAssignmentId);
        $survey = $surveyAssignment->group->survey;
        $surveyDiagnosis = $surveyAssignment->studentDiagnosis->getTranslation('scaling', 'ru');

        $json = $this->buildBaseJson($survey);
        $json['interpretation_prompt'] = $survey->interpretation_prompt;
        $json['scaling'] = $surveyDiagnosis;
        $json['questions'] = $this->buildQuestionsJson($survey, $surveyAssignmentId);

        return $json;
    }

    private function buildBaseJson($survey): array
    {
        return [
            'title' => $survey->getTranslation('title', 'ru'),
            'description' => $survey->getTranslation('description', 'ru'),
        ];
    }

    private function buildInterpretationJson($survey): mixed
    {
        $interpretation = $survey->interpretation;
        $decoded = json_decode($interpretation, true);

        return (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
            ? $decoded
            : $interpretation;
    }


    private function buildQuestionsJson($survey, int $surveyAssignmentId): array
    {
        return $survey->questions->map(function ($question) use ($surveyAssignmentId) {
            return [
                'number' => $question->number,
                'answers' => match ($question->type) {
                    SurveyQuestionTypeEnum::SingleChoice,
                    SurveyQuestionTypeEnum::DropdownChoice => $this->getSingleChoiceSelectedAnswer($question, $surveyAssignmentId),
                    SurveyQuestionTypeEnum::MultipleChoice,
                    SurveyQuestionTypeEnum::LimitedMultipleChoice => $this->getMultipleChoiceSelectedAnswers($question, $surveyAssignmentId),
                    default => [],
                },
            ];
        })->toArray();
    }

    private function getSingleChoiceSelectedAnswer($question, $surveyAssignmentId): array
    {
        $result = $question->results()->where('survey_assignment_id', $surveyAssignmentId)->first();

        return $result ? [['score' => $result->option->score]] : [];
    }

    private function getMultipleChoiceSelectedAnswers($question, $surveyAssignmentId): array
    {
        return $question->results()->where('survey_assignment_id', $surveyAssignmentId)->get()->map(fn ($result) => [
            'score' => $result->option->score,
        ])->toArray();
    }
}
