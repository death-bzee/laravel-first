<?php

namespace App\Contracts\Organization;

use Illuminate\Support\Collection;

interface SocialPassportServiceContract
{
    public function getSocialPassportSummary(int $organizationId): Collection;

    public function getSocialCountStudents(int $organizationId): int;
}
