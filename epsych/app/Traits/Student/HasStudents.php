<?php

namespace App\Traits\Student;

use App\Models\Student;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait HasStudents
{
    protected function getStudentsByClassroomAndOrganization(int $classroomId): array|Collection
    {
        return Student::where('classroom_id', $classroomId)
            ->where('organization_id', auth()->user()->organization_id)
            ->get();
    }

    protected function mapStudents(Collection $students): array
    {
        return $students->mapWithKeys(function ($student) {
            return [
                $student->id => $student->surname . ' ' . $student->name . ' ' . $student->patronymic,
            ];
        })->toArray();
    }

    /**
     * @throws Exception
     */
    protected function updateStudentAssociations(Model $model, array $studentSelected): void
    {
        // Проверка, поддерживает ли модель метод students()
        if (method_exists($model, 'students')) {
            if (empty($studentSelected)) {
                // Если нет выбранных учеников, удалить все связи
                $model->students()->detach(); // Удалить все связи с моделью
            } else {
                // Если список выбранных учеников изменился, синхронизировать его
                $model->students()->sync($studentSelected);
            }
        } else {
            throw new Exception("Модель не поддерживает отношение 'students'.");
        }
    }
}
