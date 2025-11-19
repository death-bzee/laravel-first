<?php

namespace App\Services\User;

use App\Contracts\User\UserRoleServiceContract;
use App\Enums\RoleEnum;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;

class UserRoleService implements UserRoleServiceContract
{
    public function getOrganizationsByUser(): array
    {
        $user = auth()->user();
        $organizationIds = [];

        if (
            $user->hasRole(RoleEnum::Psychologist) ||
            $user->hasRole(RoleEnum::StudentAffairsManager) ||
            $user->hasRole(RoleEnum::HomeroomTeacher)
        ) {
            $organizationIds = [$user->organization_id];
        } elseif ($user->hasRole(RoleEnum::CorrectionalServiceDistrict)) {
            $organizationIds = Organization::query()
                ->where('district_id', $user->district_id)
                ->pluck('id')
                ->toArray();
        } elseif ($user->hasRole(RoleEnum::CorrectionalServiceRegion)) {
            $organizationIds = Organization::query()
                ->where('region_id', $user->region_id)
                ->pluck('id')
                ->toArray();
        }

        return $organizationIds;
    }

    public function getUserIdsByOrganization(array $organizationIds): array
    {
        return User::query()
            ->whereIn('organization_id', $organizationIds)
            ->pluck('id')
            ->toArray();
    }

    public function applyUserFilterToQuery(Builder $query, RoleEnum $role = RoleEnum::Psychologist): Builder
    {
        $user = auth()->user();

        if ($user->hasRole($role)) {
            // Только записи текущего пользователя
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole(RoleEnum::StudentAffairsManager)) {
            // Записи всех пользователей из той же организации
            $organizationIds = $this->getOrganizationsByUser();
            $userIds = $this->getUserIdsByOrganization($organizationIds);
            $query->whereIn('user_id', $userIds);
        }

        return $query;
    }

    public function applyRoleFilter(Builder $query): Builder
    {
        $user = auth()->user();

        match (true) {
            // Если роль пользователя требует привязки к классу
            RoleEnum::requiresClasroomContains($user->getRoleNames()->first()) => $this->filterByClassrooms($query, $user),

            // Если роль пользователя связана с организацией
            RoleEnum::requiresOrganizationContains($user->getRoleNames()->first()) => $this->filterByOrganization($query, $user),

            // Если пользователь относится к роли коррекционной службы района,
            $user->hasRole(RoleEnum::CorrectionalServiceDistrict->value) => $this->filterByDistrict($query, $user),

            // Если пользователь относится к роли коррекционной службы области,
            $user->hasRole(RoleEnum::CorrectionalServiceRegion->value) => $this->filterByRegion($query, $user),

            // Если ни одно из условий не выполнено, возвращается оригинальный запрос,
            default => $query,
        };

        return $query;
    }

    private function filterByClassrooms(Builder $query, $user): Builder
    {
        return $query
            ->whereIn('classroom_id', $user->classrooms()->pluck('classrooms.id'))
            ->where('organization_id', $user->organization_id);
    }

    private function filterByOrganization(Builder $query, $user): Builder
    {
        return $query->where('organization_id', $user->organization_id);
    }

    private function filterByDistrict(Builder $query, $user): Builder
    {
        $organizationIds = Organization::query()->where('district_id', $user->district_id)->pluck('id');

        return $query->whereIn('organization_id', $organizationIds);
    }

    private function filterByRegion(Builder $query, $user): Builder
    {
        $organizationIds = Organization::query()->where('region_id', $user->region_id)->pluck('id');

        return $query->whereIn('organization_id', $organizationIds);
    }
}
