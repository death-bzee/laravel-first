<?php

namespace App\Services\User;

use App\Repositories\User\ResponsibilityAreaRepository;

class ResponsibilityAreaService
{
    public function __construct(
        protected ResponsibilityAreaRepository $repository
    ) {}

    public function getAccessibleDistricts(): array
    {
        $regionId = auth()->user()->region_id;

        return $this->repository
            ->getDistrictsByRegionId($regionId)
            ->toArray();
    }

    public function getAccessibleOrganizations(): array
    {
        $districtIds = array_keys($this->getAccessibleDistricts());

        return $this->repository
            ->getOrganizationsByDistrictIds($districtIds)
            ->toArray();
    }
}
