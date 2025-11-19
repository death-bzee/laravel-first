<?php

namespace App\Console\Commands\Survey;

use Illuminate\Console\Command;
use App\Jobs\Survey\Ai\GenerateSurveyDiagnosisJob;
use App\Jobs\Survey\Ai\GenerateSurveyScalingJob;
use App\Jobs\Survey\Ai\GenerateSurveyInterpretationJob;

class SurveyGenerateCommand extends Command
{
    protected $signature = 'survey:generate
                            {surveyId : ID назначения опроса (survey_assignment_id)}
                            {--diagnosis : Запустить диагностику ученика}
                            {--scale : Запустить шкалирование}
                            {--interpret : Запустить интерпретацию}';

    protected $description = 'Запускает выбранные джобы по обработке опроса: диагностика, шкалирование, интерпретация';

    public function handle(): void
    {
        $surveyId = (int) $this->argument('surveyId');

        if ($this->option('diagnosis')) {
            GenerateSurveyDiagnosisJob::dispatch($surveyId);
            $this->info("✔ GenerateStudentDiagnosisJob запущен");
        }

        if ($this->option('scale')) {
            GenerateSurveyScalingJob::dispatch($surveyId);
            $this->info("✔ GenerateSurveyScalingJob запущен");
        }

        if ($this->option('interpret')) {
            GenerateSurveyInterpretationJob::dispatch($surveyId);
            $this->info("✔ GenerateSurveyInterpretationJob запущен");
        }

        if (! $this->option('diagnosis') && ! $this->option('scale') && ! $this->option('interpret')) {
            $this->warn('⚠ Укажите хотя бы один флаг: --diagnosis, --scale, --interpret');
        }
    }
}
