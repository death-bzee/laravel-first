<?php

namespace App\Models;

use App\Models\Concerns\EducationLevel;
use App\Models\Concerns\Relation\StudentSocialPassport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Translatable\HasTranslations;

class SocialPassport extends Model
{
    use HasTranslations;

    protected $fillable = ['active','title', 'sort'];

    public array $translatable = ['title'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_social_passports')
            ->withPivot(['value'])
            ->withTimestamps();
    }

    public function educationLevels(): HasManyThrough
    {
        return $this->hasManyThrough(
            EducationLevel::class,
            StudentSocialPassport::class,
            'social_passport_id',
            'id',
            'id',
            'value_foreign_id'
        );
    }
}
