<?php

namespace App\Models;

use App\Models\Concerns\EducationLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentParent extends Model
{
    protected $fillable = [
        'student_id',
        'surname',
        'name',
        'patronymic',
        'education_level_id',
        'job',
        'address',
        'phone',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }
}
