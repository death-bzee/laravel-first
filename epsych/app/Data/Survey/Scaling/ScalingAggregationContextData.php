<?php

namespace App\Data\Survey\Scaling;

use Spatie\LaravelData\Data;

class ScalingAggregationContextData extends Data
{
    public function __construct(
        public int $surveyId,
        public ?int $districtId = null,
        public ?int $organizationId = null,
        public ?int $classroomId = null,
    ) {}
}
