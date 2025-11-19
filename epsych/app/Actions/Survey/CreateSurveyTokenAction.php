<?php

namespace App\Actions\Survey;

use App\Models\AccessToken;
use App\Models\Concerns\Relation\AccessTokenStudentSurvey;
use App\Models\Student;
use App\Models\Survey\SurveyAssignment;
use Illuminate\Support\Str;

class CreateSurveyTokenAction
{
    /**
     * Выполняет создание токена и записи в таблице access_token_student_surveys.
     *
     * @param SurveyAssignment $surveyAssignment
     * @param Student|null $student
     * @return AccessTokenStudentSurvey
     */
    public function handle(SurveyAssignment $surveyAssignment, ?Student $student): AccessTokenStudentSurvey
    {
        // Проверяем, есть ли уже запись
        $existing = AccessTokenStudentSurvey::where('survey_assignment_id', $surveyAssignment->id)
            ->where('student_id', $student?->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        $token = AccessToken::query()->create([
            'token' => Str::random(15)
        ]);

        return AccessTokenStudentSurvey::query()->create([
            'access_token_id' => $token->id,
            'survey_assignment_id' => $surveyAssignment->id,
            'student_id' => $student?->id,
            'access_code' => ''
        ]);
    }

}
