<?php

namespace App\Traits\Classroom;

use App\Models\Classroom;

trait HasClassrooms
{
    public function setClassRooms(): array
    {
        return Classroom::whereHas('students', function ($query) {
            $query->where('organization_id', auth()->user()->organization_id);
        })
        ->get()
        ->mapWithKeys(function ($classroom) {
            return [
                $classroom->id => $classroom->grade . $classroom->letter,
            ];
        })
        ->toArray();
    }
}
