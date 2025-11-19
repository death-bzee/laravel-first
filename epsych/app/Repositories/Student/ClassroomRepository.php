<?php

namespace App\Repositories\Student;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ClassroomRepository
{
    /**
     * Базовая query доступных классов через студентов (учитывая организацию).
     *
     * @param array $organizationIds
     * @return Builder
     */
    public function getAccessibleClassroomsQuery(array $organizationIds): Builder
    {
        return Classroom::query()
            ->whereHas('students', function ($query) use ($organizationIds) {
                $query->whereIn('organization_id', $organizationIds);
            })
            ->select('id', 'classroom_full_name');
    }

    /**
     * Получаем список доступных классов [id => "Класс"]
     *
     * @param array $organizationIds
     * @return Collection
     */
    public function getAccessibleClassrooms(array $organizationIds): Collection
    {
        return $this->getAccessibleClassroomsQuery($organizationIds)->pluck('classroom_full_name', 'id');
    }
}
