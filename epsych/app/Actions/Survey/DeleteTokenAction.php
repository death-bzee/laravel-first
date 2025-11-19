<?php

namespace App\Actions\Survey;

use App\Models\Concerns\Relation\AccessTokenStudentSurvey;
use App\Models\Survey\SurveyAssignment;

class DeleteTokenAction
{
    /**
     * Удаляет токен, связанный с записью в AccessTokenStudentSurvey.
     *
     * @param int $surveyGroupAssignmentId
     * @return void
     */
    public function handle(int $surveyGroupAssignmentId): void
    {
        $surveyAssignments = SurveyAssignment::where('group_id', $surveyGroupAssignmentId)->pluck('id');
        $surveys = AccessTokenStudentSurvey::whereIn('survey_assignment_id', $surveyAssignments)->get();

        foreach ($surveys as $survey) {
            if ($survey->accessToken) {
                // Удаляем связанный токен
                $survey->accessToken->delete();
            }
            // Удаляем запись в AccessTokenStudentSurvey
            $survey->delete();
        }
    }
}
