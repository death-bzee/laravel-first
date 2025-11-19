<?php

namespace App\Data\Survey\Report;

class SurveyReportData
{
    public function __construct(
        public array $reportData = [],
        public array $classesSummary = [],
        public array $testedByClass = [],
        public ?int $schoolTotalStudents = null,
        public array $totals = [],
        public ?string $exportClass = null, // FQCN экспорт-класса
        public ?string $exportTitle = null,
        public ?string $bladeView = null // Заголовок для экспорта
    ) {}
}
