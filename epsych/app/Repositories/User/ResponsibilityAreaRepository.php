<?php

namespace App\Repositories\User;

use App\Models\Concerns\District;
use App\Models\Organization;
use Illuminate\Support\Collection;

class ResponsibilityAreaRepository {
    public function getDistrictsByRegionId(int $regionId): Collection
    {
        return District::query()
            ->where('region_id', $regionId)
            ->pluck('title', 'id');
    }

    public function getOrganizationsByDistrictIds(array $districtIds): Collection
    {
        return Organization::query()
            ->whereIn('district_id', $districtIds)
            ->pluck('title', 'id');
    }
}
