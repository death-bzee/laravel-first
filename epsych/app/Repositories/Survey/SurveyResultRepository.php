<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveyResult;
use Illuminate\Support\Collection;

class SurveyResultRepository
{
    /**
     * Вернуть результаты учеников организациям по конкретному survey_id
     */
    public function getResultsByOrganizationsAndSurvey(int|array|Collection $organizationIds, int $surveyId): Collection
    {
        $organizationIds = collect($organizationIds)->toArray();

        return SurveyResult::query()
            ->with([
                'question:id,title,survey_id',
                'option:id,title,question_id',
                'surveyAssignment.student:id,organization_id,gender',
                'surveyAssignment.groupAssignment.survey:id,title',
            ])
            ->whereHas('surveyAssignment.student', function ($studentQuery) use ($organizationIds) {
                $studentQuery->whereIn('organization_id', $organizationIds);
            })
            ->whereHas('surveyAssignment.groupAssignment', function ($groupQuery) use ($surveyId) {
                $groupQuery->where('survey_id', $surveyId);
            })
            ->get();
    }
}
