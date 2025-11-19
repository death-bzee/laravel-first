<?php

namespace App\Jobs\Survey\Ai;

use App\Models\Survey\SurveyAssignment;
use App\Models\Survey\SurveyStudentDiagnosis;
use App\Services\Survey\Ai\SurveyInterpretationService;
use App\Services\Survey\SurveyResultService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateSurveyInterpretationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Сколько раз Laravel попробует выполнить задачу
     */
    public $tries = 1;

    /**
     * Задержка между повторами (в секундах)
     */
    public $backoff = 5;

    protected int $surveyId;

    public function __construct(int $surveyId)
    {
        $this->surveyId = $surveyId;
    }

    public function handle(
        SurveyResultService $surveyResultService,
        SurveyInterpretationService $surveyInterpretationService
    ): void {
        try {
            $surveyAssignment = SurveyAssignment::find($this->surveyId);

            if (! $surveyAssignment) {
                Log::warning("InterpretationJob: SurveyAssignment {$this->surveyId} не найден, job пропущен.");
                return;
            }

            $surveyAssignment->refresh();
            $survey = $surveyAssignment->group->survey ?? null;

            if (! $survey || empty($survey->interpretation_prompt)) {
                Log::info("InterpretationJob: Survey {$this->surveyId} не имеет interpretation_prompt, job пропущен.");
                return;
            }

            // Формируем JSON
            $surveyResultJson = $surveyResultService->getSurveyResultInterpretationJson($this->surveyId);

            // Отправляем в сервис (GPT/OpenAI)
            $interpretationResult = $surveyInterpretationService->generateInterpretation($surveyResultJson);

            // Сохраняем результат
            SurveyStudentDiagnosis::updateOrCreate(
                ['survey_assignment_id' => $this->surveyId],
                ['interpretation' => $interpretationResult['interpretation'] ?? null]
            );

            Log::info("✅ InterpretationJob: диагноз сохранён для SurveyAssignment {$this->surveyId}");
        } catch (\Throwable $e) {
            Log::error("❌ Ошибка в InterpretationJob (SurveyAssignment {$this->surveyId}): {$e->getMessage()}");

            // Повторим через backoff, если не исчерпаны попытки
            if ($this->attempts() < $this->tries) {
                $this->release($this->backoff);
                return;
            }

            // Если уже все попытки использованы — падаем в failed_jobs
            throw $e;
        }
    }
}
