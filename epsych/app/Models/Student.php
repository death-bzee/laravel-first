<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Models\Concerns\Language;
use App\Models\Concerns\Nationality;
use App\Models\Concerns\Relation\StudentSocialPassport;
use App\Models\Survey\SurveyAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Student extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'photo',
        'surname',
        'name',
        'patronymic',
        'iin',
        'birthday',
        'phone',
        'incidents',
        'organization_id',
        'classroom_id',
        'language_id',
        'gender',
        'nationality_id',
        'family_size',
    ];

    protected $casts = [
        'gender' => GenderEnum::class,
        //'iin'       => 'encrypted',
        //'phone'    => 'encrypted',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(SurveyAssignment::class);
    }

    public function parent(): HasOne
    {
        return $this->hasOne(StudentParent::class);
    }

    public function studentSocialPassports(): HasMany
    {
        return $this->hasMany(StudentSocialPassport::class, 'student_id');
    }

    public function consultationJournals(): MorphMany
    {
        return $this->morphMany(ConsultationJournal::class, 'consultable');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('student_avatars')->singleFile(); // Коллекция для аватара
        $this->addMediaCollection('student_document_incidents');
        $this->addMediaCollection('student_document_performance');
        $this->addMediaCollection('student_document_portfolio');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('avatar')
            ->fit(Fit::Crop, 300, 300) // Обрезка до 300x300
            ->performOnCollections('student_avatars'); // Выполняется только для коллекции 'avatars'
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim(
                ucfirst($this->surname) . ' ' .
                ucfirst($this->name) . ' ' .
                ($this->patronymic ? ucfirst($this->patronymic) : '')
            )
        );
    }

    protected function fullNameClassroom(): Attribute
    {
        return Attribute::make(
            get: fn() => trim(
                ucfirst($this->surname) . ' ' .
                ucfirst($this->name) . ' ' .
                ($this->patronymic ? ucfirst($this->patronymic) : '') .
                ($this->classroom?->classroom_full_name
                    ? ' (' . $this->classroom->classroom_full_name . ')'
                    : ''
                )
            )
        );
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getFirstMediaUrl('student_avatars') ?: null
        );
    }

    protected function birthdayFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->birthday
                ? Carbon::parse($this->birthday)->translatedFormat('d F Y')
                : null
        );
    }

}
