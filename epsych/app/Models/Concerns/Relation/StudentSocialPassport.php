<?php

namespace App\Models\Concerns\Relation;

use App\Models\Concerns\EducationLevel;
use App\Models\SocialPassport;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSocialPassport extends Model
{
    protected $fillable = [
        'student_id',
        'social_passport_id',
        'value',
    ];

    protected $casts = [
        'value' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function socialPassport(): BelongsTo
    {
        return $this->belongsTo(SocialPassport::class);
    }
}
