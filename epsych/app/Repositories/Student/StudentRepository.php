<?php

namespace App\Repositories\Student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StudentRepository
{
    /**
     * Получает список учеников по заданным параметрам (организация и класс).
     */
    public function getStudents(?int $organizationId, ?int $classroomId = null): Collection
    {
        if (! $organizationId) {
            return collect(); // Если нет организации, возвращаем пустую коллекцию
        }

        return Student::query()
            ->where('organization_id', $organizationId)
            ->when($classroomId, fn ($query) => $query->where('classroom_id', $classroomId))
            ->with('classroom') // Подгружаем класс
            ->get()
            ->mapWithKeys(fn ($student) => [
                $student->id => $this->formatStudentName($student),
            ]);
    }

    public function getStudentsQuery(int $organizationId, ?int $classroomId = null): Builder
    {
        return Student::query()
            ->with('classroom')
            ->where('organization_id', $organizationId)
            ->when($classroomId, fn ($q) => $q->where('classroom_id', $classroomId));
    }

    /**
     * Форматирует ФИО ученика с классом.
     */
    private function formatStudentName(Student $student): string
    {
        return "{$student->surname} {$student->name} ".
            ($student->patronymic ?? '').
            ($student->classroom ? " ({$student->classroom->classroomName})" : '');
    }

    /**
     * Количество всех студентов организации
     */
    public function getStudentCount(int $organizationId): int
    {
        return Student::query()
            ->where('organization_id', $organizationId)
            ->count();
    }

    /**
     * Количество студентов организации в 5-11 классах
     */
    public function getStudentCountInGrades(int $organizationId, array $grades = [5, 6, 7, 8, 9, 10, 11]): int
    {
        return Student::query()
            ->where('organization_id', $organizationId)
            ->whereHas('classroom', fn ($q) => $q->whereIn('grade', $grades))
            ->count();
    }

    /**
     * Количество мальчиков и девочек в организации
     */
    public function getStudentCountByGender(int $organizationId): Collection
    {
        return Student::query()
            ->selectRaw('gender, COUNT(*) as total')
            ->where('organization_id', $organizationId)
            ->groupBy('gender')
            ->pluck('total', 'gender'); // вернет коллекцию: ['male' => X, 'female' => Y]
    }

	/**
     * Количество всех студентов по множеству организаций
     */
    public function getStudentCountByOrganizations(Collection|array $organizationIds): int
    {
        return Student::query()
            ->whereIn('organization_id', collect($organizationIds)->toArray())
            ->count();
    }

    /**
     * Количество студентов 5–11 классов по множеству организаций
     */
    public function getStudentCountInGradesByOrganizations(Collection|array $organizationIds, array $grades = [5, 6, 7, 8, 9, 10, 11]): int
    {
        return Student::query()
            ->whereIn('organization_id', collect($organizationIds)->toArray())
            ->whereHas('classroom', fn ($q) => $q->whereIn('grade', $grades))
            ->count();
    }
}
