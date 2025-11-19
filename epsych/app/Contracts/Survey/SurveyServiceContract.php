<?php

namespace App\Contracts\Survey;

use App\Models\Survey\SurveyAssignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface SurveyServiceContract
{
    public function getSurveysByClassroom(?int $classroomId = null): Collection;
    public function getSurveyAssignment(int $survey_assignment_id): Model|Builder|null;
    public function getLastNumberAnsweredQuestion(SurveyAssignment $surveyAssignment): int;
    public function getNextNumberQuestion(SurveyAssignment $surveyAssignment): int;
    public function getPreviousNumberQuestion(SurveyAssignment $surveyAssignment): int;
    public function setAnswer(array $data): void;
    public function setSurveyComplete(SurveyAssignment $surveyAssignment): void;
}
