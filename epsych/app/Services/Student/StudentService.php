<?php

namespace App\Services\Student;

use App\Contracts\Student\StudentServiceContract;
use App\Contracts\User\UserRoleServiceContract;
use App\Repositories\Student\StudentRepository;
use Illuminate\Database\Eloquent\Builder;

class StudentService implements StudentServiceContract
{
    protected UserRoleServiceContract $userService;
    protected StudentRepository $studentRepository;

    public function __construct(
        UserRoleServiceContract $userService,
        StudentRepository       $studentRepository
    )
    {
        $this->userService = $userService;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Получаем список учеников текущего пользователя по организации и (опционально) классу.
     *
     * @param int|null $classroomId
     * @return array [id => "ФИО (Класс)"]
     */
    public function getStudents(?int $classroomId = null): array
    {
        $organizationId = auth()->user()->organization_id;

        return $this->studentRepository
            ->getStudents($organizationId, $classroomId)
            ->toArray();
    }

    /**
     * Получаем query для выборки учеников текущего пользователя.
     *
     * @param int|null $classroomId
     * @return Builder
     */
    public function getStudentsQuery(?int $classroomId = null): Builder
    {
        $organizationId = auth()->user()->organization_id;

        return $this->studentRepository
            ->getStudentsQuery($organizationId, $classroomId);
    }
}
