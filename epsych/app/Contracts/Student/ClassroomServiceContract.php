<?php

namespace App\Contracts\Student;

use Illuminate\Database\Eloquent\Builder;

interface ClassroomServiceContract
{
    public function getAccessibleClassrooms(): array;
    public function getAccessibleClassroomsQuery(): Builder;
}
