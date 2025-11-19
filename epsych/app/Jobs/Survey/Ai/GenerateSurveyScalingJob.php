<?php

namespace App\Jobs\Survey\Ai;

use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyStudentDiagnosis;
use App\Services\Survey\SurveyResultService;
use App\Services\Survey\Ai\SurveyScalingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use JsonException;

class GenerateSurveyScalingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Количество попыток перед fail
     */
    public $tries = 1;

    /**
     * Задержка перед повтором (секунд)
     */
    public $backoff = 5;

    protected int $surveyId;

    public function __construct(int $surveyId)
    {
        $this->surveyId = $surveyId;
    }

    public function handle(
        SurveyResultService $surveyResultService,
        SurveyScalingService $surveyScalingService
    ): void {
        try {
            $surveyAssignment = SurveyAssignment::find($this->surveyId);

            if (! $surveyAssignment) {
                Log::warning("ScalingJob: SurveyAssignment {$this->surveyId} не найден, job пропущен.");
                return;
            }

            $surveyAssignment->refresh();
            $survey = $surveyAssignment->group->survey ?? null;

            if (! $survey || empty($survey->scaling_prompt)) {
                Log::info("ScalingJob: SurveyAssignment {$this->surveyId} не имеет scaling_prompt, job пропущен.");
                return;
            }

            // Формируем JSON для анализа
            $surveyResultJson = $surveyResultService->getSurveyResultScalingJson($this->surveyId);

            // Отправляем в сервис масштабирования (скорее всего тут может падать)
            $scalingResult = $surveyScalingService->calculateScaling($surveyResultJson);

            // Сохраняем результат
            SurveyStudentDiagnosis::updateOrCreate(
                ['survey_assignment_id' => $this->surveyId],
                ['scaling' => $scalingResult]
            );

            Log::info("✅ ScalingJob: scaling сохранён для SurveyAssignment {$this->surveyId}");
        } catch (\Throwable $e) {
            Log::error("❌ Ошибка в ScalingJob (SurveyAssignment {$this->surveyId}): {$e->getMessage()}");

            // Повторим задачу с задержкой, если ещё есть попытки
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff);
                return;
            }

            // Если попытки исчерпаны — уводим job в failed_jobs
            throw $e;
        }
    }
}
