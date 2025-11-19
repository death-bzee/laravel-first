<?php

namespace App\Services\Survey\Report;

use App\Contracts\Survey\Report\SurveyReportContract;
use App\Models\Survey\Survey;

class SurveyReportManager
{
    /**
     * @var SurveyReportContract[]
     */
    protected array $reports = [];

    /**
     * @param iterable<SurveyReportContract> $reports
     */
    public function __construct(iterable $reports = [])
    {
        foreach ($reports as $report) {
            $this->reports[] = $report;
        }
    }

    public function forSurvey(?Survey $survey): ?SurveyReportContract
    {
        if (! $survey) {
            return null;
        }

        foreach ($this->reports as $report) {
            if ($report->supports($survey)) {
                return $report;
            }
        }

        return null;
    }
}
