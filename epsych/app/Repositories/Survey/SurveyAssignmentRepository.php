<?php

namespace App\Repositories\Survey;

use App\Models\Survey\SurveyAssignment;

class SurveyAssignmentRepository
{
    /**
     * Обновляет список назначений учеников для группы
     *
     * @param int $groupId
     * @param array $studentIds
     * @return void
     */
    public function updateAssignments(int $groupId, array $studentIds): void
    {
        // Удаляем старые назначения, если они не выбраны
        SurveyAssignment::query()
            ->where('group_id', $groupId)
            ->whereNotIn('student_id', $studentIds)
            ->delete();

        // Формируем новые назначения
        $newAssignments = collect($studentIds)->map(fn ($studentId) => [
            'group_id' => $groupId,
            'student_id' => $studentId,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        // Используем insertOrIgnore, чтобы избежать дубликатов
        SurveyAssignment::query()->insertOrIgnore($newAssignments);
    }

    public function getSurveyAssignment(int $id): SurveyAssignment
    {
        return SurveyAssignment::with([
            'group.survey.questions.options',
            'group.survey.levelGroup.values',
            'studentDiagnosis',
        ])->findOrFail($id);
    }
}
