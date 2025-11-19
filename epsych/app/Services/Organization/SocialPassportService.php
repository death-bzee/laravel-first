<?php

namespace App\Services\Organization;

use App\Contracts\Organization\SocialPassportServiceContract;
use App\Repositories\SocialPassportRepository;
use Illuminate\Support\Collection;

class SocialPassportService implements SocialPassportServiceContract
{
    protected SocialPassportRepository $repository;

    public function __construct(SocialPassportRepository $repository)
    {
        $this->repository = $repository;
    }

    private function groupStudentsBySocialPassport(int $organizationId): Collection
    {
        return $this->repository->getSocialPassports($organizationId)
            ->flatMap(fn($student) => $student->studentSocialPassports)
            ->groupBy(fn($socialPassport) => $socialPassport->socialPassport->id);
    }

    public function getSocialPassportSummary(int $organizationId): Collection
    {
        return $this->groupStudentsBySocialPassport($organizationId)
            ->mapWithKeys(fn($group, $socialPassportId) => [
                $this->repository->getSocialPassports($organizationId)
                    ->flatMap(fn($student) => $student->studentSocialPassports)
                    ->firstWhere('socialPassport.id', $socialPassportId)?->socialPassport->title
                    => $group->count(),
            ]);
    }

    public function getSocialCountStudents(int $organizationId): int
    {
        return $this->groupStudentsBySocialPassport($organizationId)
            ->flatMap(fn($group) => $group)
            ->unique('student_id') // Исключаем дубликаты студентов
            ->count();
    }

    /**
     * Возвращает коллекцию с количеством студентов по уровню образования родителей.
     */
    public function getStudentsGroupedByEducationLevel(int $organizationId): Collection
    {
        return $this->repository->getStudentsCountByEducationLevel($organizationId);
    }

}
