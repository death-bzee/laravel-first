<?php

namespace App\Jobs\Survey\Ai;

use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyStudentDiagnosis;
use App\Services\Survey\Ai\SurveyDiagnosisService;
use App\Services\Survey\SurveyResultService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateSurveyDiagnosisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Сколько раз пытаться выполнить job
     */
    public $tries = 1;

    /**
     * Задержка между попытками (секунды)
     */
    public $backoff = 5;

    protected int $surveyId;

    public function __construct(int $surveyId)
    {
        $this->surveyId = $surveyId;
    }

    public function handle(
        SurveyResultService $surveyResultService,
        SurveyDiagnosisService $surveyDiagnosisService
    ): void {
        try {
            $surveyAssignment = SurveyAssignment::find($this->surveyId);

            if (! $surveyAssignment) {
                Log::warning("GenerateSurveyDiagnosisJob: SurveyAssignment {$this->surveyId} не найден, job пропущен.");
                return;
            }

            $surveyAssignment->refresh();
            $survey = $surveyAssignment->group->survey ?? null;

            if (! $survey || empty($survey->interpretation)) {
                Log::info("GenerateSurveyDiagnosisJob: Survey {$this->surveyId} не имеет interpretation, job пропущен.");
                return;
            }

            // Получаем данные для анализа
            $surveyResultJson = $surveyResultService->getSurveyResultDiagnosisJson($this->surveyId);

            // Запрашиваем у GPT диагноз
            $resultGPT = $surveyDiagnosisService->generateStudentDiagnosis($surveyResultJson);

            // Сохраняем или обновляем результат
            SurveyStudentDiagnosis::updateOrCreate(
                ['survey_assignment_id' => $this->surveyId],
                [
                    'diagnosis' => $resultGPT['diagnosis'] ?? null,
                    'explained_diagnosis' => $resultGPT['explained_diagnosis'] ?? null,
                ]
            );

            Log::info("✅ GenerateSurveyDiagnosisJob: диагноз сохранён для SurveyAssignment {$this->surveyId}");
        } catch (\Throwable $e) {
            Log::error("❌ Ошибка в GenerateSurveyDiagnosisJob (SurveyAssignment {$this->surveyId}): {$e->getMessage()}");

            // Если это сетевая ошибка или временная проблема → пробуем повторить
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff);
                return;
            }

            // Если исчерпаны попытки → пробрасываем дальше, попадёт в failed_jobs
            throw $e;
        }
    }
}
