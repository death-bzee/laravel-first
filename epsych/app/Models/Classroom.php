<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = ['grade', 'letter', 'sort'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'classroom_user');
    }

    protected function classroomName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->grade}{$this->letter}"
        );
    }
}
