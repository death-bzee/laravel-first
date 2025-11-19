<?php

namespace App\Contracts\Survey\Report;

use App\Data\Survey\Report\SurveyReportData;
use App\Models\Survey\Survey;
use Illuminate\Support\Collection;

interface SurveyReportContract
{
    /**
     * Определяет, подходит ли этот сервис для указанной методики.
     */
    public function supports(Survey $survey): bool;

    /**
     * Построение отчёта по данным шкалирования.
     *
     * $options — сюда передаём всё контекстное:
     * - role           => 'region' | 'district' | 'psychologist'
     * - district_id    => ?int
     * - organization_id=> ?int
     * - classroom_id   => ?int
     * - survey_id      => int
     * - user           => \App\Models\User
     */
    public function build(Collection $scalingData, array $options = []): SurveyReportData;
}
