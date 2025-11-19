<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SurveyReportType extends Model
{

    use HasTranslations;

    protected $fillable = ['title'];

    public array $translatable = ['title'];
}
