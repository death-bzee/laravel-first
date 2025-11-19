<?php

namespace App\Repositories;


use App\Models\Student;
use Illuminate\Support\Collection;

class SocialPassportRepository
{
    /**
     * Получаем сгруппированные социальные паспорта, относящиеся к организации
     *
     * @param int $organizationId
     * @return Collection
     */
    public function getSocialPassports(int $organizationId): Collection
    {
        return Student::query()
            ->where('organization_id', $organizationId)
            ->whereHas('studentSocialPassports')
            ->with(['studentSocialPassports' => function ($query) {
                $query->with(['socialPassport:id,title']);
            }])
            ->get();
    }

    /**
     * Получаем количество студентов, сгруппированное по уровню образования родителей.
     *
     * @param int $organizationId
     * @return Collection
     */
    public function getStudentsCountByEducationLevel(int $organizationId): Collection
    {
        return Student::query()
            ->where('organization_id', $organizationId)
            ->whereHas('parent', function ($query) {
                $query->whereNotNull('education_level_id'); // Исключаем родителей без образования
            })
            ->with('parent.educationLevel:id,title')
            ->get()
            ->groupBy(fn($student) => $student->parent->educationLevel->title) // Группировка по названию уровня образования
            ->map(fn($students, $educationLevel) => [
                'education_level' => $educationLevel,
                'students_count' => $students->count(),
            ])
            ->values(); // Преобразуем коллекцию в удобный формат
    }
}
