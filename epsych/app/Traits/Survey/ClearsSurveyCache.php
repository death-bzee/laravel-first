<?php

namespace App\Traits\Survey;

use App\Contracts\Survey\SurveyServiceContract;
use App\Models\Survey\Survey;

trait ClearsSurveyCache
{
    protected static function bootClearsSurveyCache(): void
    {
        static::saved(function ($model) {
            app(SurveyServiceContract::class)->clearSurveyCache($model->getRelatedSurvey());
        });

        static::deleted(function ($model) {
            app(SurveyServiceContract::class)->clearSurveyCache($model->getRelatedSurvey());
        });
    }

    /**
     * Метод, который должен возвращать связанный объект Survey.
     * Каждая модель, использующая этот трейд, должна реализовать этот метод.
     *
     * @return Survey
     */
    abstract protected function getRelatedSurvey(): Survey;
}
