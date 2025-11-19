<?php

namespace App\Repositories\Survey;

use App\Models\Survey\Survey;
use Illuminate\Support\Collection;

class SurveyRepository
{
    /**
     * Получает список опросов по переданным student_ids.
     *
     * @param array $studentIds
     * @return Collection
     */
    public function getSurveysByStudentIds(array $studentIds): Collection
    {
        return Survey::query()
            ->whereHas('groups.assignments', function ($query) use ($studentIds) {
                $query->whereIn('student_id', $studentIds);
            })
            ->get();
    }

}
