<?php

namespace App\Actions\Survey;

use App\Enums\Survey\SurveyGroupAssignmentStatusEnum;
use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyGroupAssignment;

class CompleteSurveyGroupAction
{
    public function handle(int $surveyGroupId): void
    {
        // Проверяем, если ли хоть один SurveyAssignment без даты в completed_at
        $isNotCompleted = SurveyAssignment::where('group_id', $surveyGroupId)
            ->whereNull('completed_at')
            ->first();

        // Если таких записей нет, обновляем статус группы на 'Completed'
        if(!$isNotCompleted) {
            SurveyGroupAssignment::where('id', $surveyGroupId)
                ->update([
                    'status' => SurveyGroupAssignmentStatusEnum::Completed,
                    'completed_at' => now()
                ]);
        }

    }
}
