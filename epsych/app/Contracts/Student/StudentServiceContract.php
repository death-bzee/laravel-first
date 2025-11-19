<?php

namespace App\Contracts\Student;

use Illuminate\Database\Eloquent\Builder;

interface StudentServiceContract
{
    /**
     * Получаем список учеников по организации и, опционально, классу.
     *
     * @param int|null $classroomId
     * @return array [id => "ФИО (Класс)"]
     */
    public function getStudents(?int $classroomId = null): array;

    public function getStudentsQuery(?int $classroomId = null): Builder;
}
