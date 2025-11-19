<?php

namespace App\Repositories\Organization;

use App\Models\Organization;
use Illuminate\Support\Collection;

class OrganizationRepository
{
    /**
     * Вернуть все организации региона с привязкой к district.
     */
    public function getByRegion(int $regionId): Collection
    {
        return Organization::query()
            ->where('region_id', $regionId)
            ->with('district:id,title')
            ->get(['id', 'district_id', 'title']);
    }

    /**
     * Вернуть все организации по district_id.
     */
    public function getByDistrict(int $districtId): Collection
    {
        return Organization::query()
            ->where('district_id', $districtId)
            ->with('district:id,title')
            ->get(['id', 'district_id', 'title']);
    }
}
