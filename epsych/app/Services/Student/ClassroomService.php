<?php

namespace App\Services\Student;

use App\Contracts\User\UserRoleServiceContract;
use App\Repositories\Student\ClassroomRepository;
use Illuminate\Database\Eloquent\Builder;

class ClassroomService
{
    protected UserRoleServiceContract $userService;

    protected ClassroomRepository $classroomRepository;

    public function __construct(UserRoleServiceContract $userService, ClassroomRepository $classroomRepository)
    {
        $this->userService = $userService;
        $this->classroomRepository = $classroomRepository;
    }

    /**
     * Получаем список доступных классов через студентов (учитывая организацию).
     *
     * @return array [id => "Класс"]
     */
    public function getAccessibleClassrooms(): array
    {
        $organizationIds = $this->userService->getOrganizationsByUser();

        return $this->classroomRepository
            ->getAccessibleClassrooms($organizationIds)
            ->toArray();
    }

    /**
     * Получаем query для выборки доступных классов (для использования в Filament).
     */
    public function getAccessibleClassroomsQuery(): Builder
    {
        $organizationIds = $this->userService->getOrganizationsByUser();

        return $this->classroomRepository
            ->getAccessibleClassroomsQuery($organizationIds);
    }
}
