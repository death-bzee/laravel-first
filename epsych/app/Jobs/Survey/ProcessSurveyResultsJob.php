<?php

namespace App\Jobs\Survey;

use App\Jobs\Survey\Ai\GenerateSurveyDiagnosisJob;
use App\Jobs\Survey\Ai\GenerateSurveyInterpretationJob;
use App\Jobs\Survey\Ai\GenerateSurveyScalingJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class ProcessSurveyResultsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $surveyAssignmentId
    ) {}

    public function handle(): void
    {
        Bus::chain([
            new GenerateSurveyDiagnosisJob($this->surveyAssignmentId),
            new GenerateSurveyScalingJob($this->surveyAssignmentId),
            new GenerateSurveyInterpretationJob($this->surveyAssignmentId),
        ])->dispatch();
    }
}
