<?php

namespace App\Data\Survey\Scaling;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Data;

class ScalingResultItemData extends Data
{
    public function __construct(
        #[BooleanType]
        public bool $isGeneral,
        public bool $isRisk,
        public string $scaleName,
        public string $levelName,
        public int $score,
    ) {}
}
