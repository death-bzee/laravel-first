<?php

namespace App\Contracts\User;

use Illuminate\Contracts\Database\Eloquent\Builder;

interface UserRoleServiceContract
{
    public function getOrganizationsByUser(): array;
    public function getUserIdsByOrganization(array $organizationIds): array;
    public function applyRoleFilter(Builder $query): Builder;
}
