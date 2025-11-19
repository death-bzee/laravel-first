<?php

namespace App\Services\Survey;

use App\Contracts\Survey\SurveyGroupAssignmentServiceContract;
use App\Models\Survey\SurveyGroupAssignment;

class SurveyGroupAssignmentService implements SurveyGroupAssignmentServiceContract
{
    public function findByUniqueCode(string $code): ?SurveyGroupAssignment
    {
        return SurveyGroupAssignment::query()
            ->where('unique_code', $code)
            ->first();
    }
}
