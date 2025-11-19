<?php

namespace App\Contracts\Survey;

use App\Models\Survey\SurveyGroupAssignment;

interface SurveyGroupAssignmentServiceContract
{
    public function findByUniqueCode(string $code): ?SurveyGroupAssignment;
}
