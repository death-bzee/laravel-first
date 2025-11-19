<?php

namespace App\Jobs\Survey;

use App\Models\Concerns\Relation\AccessTokenStudentSurvey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateAccessCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $surveyId;

    /**
     * Create a new job instance.
     */

    /**
     * Принимаем только ID
     */
    public function __construct(int $surveyId)
    {
        $this->surveyId = $surveyId;
    }
    /**
     * Выполнение job
     */
    public function handle(): void
    {
        $survey = AccessTokenStudentSurvey::find($this->surveyId);

        if (! $survey) {
            Log::warning("AccessTokenStudentSurvey [{$this->surveyId}] не найден, job пропущен.");
            return;
        }

        $code = $this->generateUniqueCode($survey->survey_assignment_id);

        $survey->update(['access_code' => $code]);

        Log::info("Сгенерирован access_code для AccessTokenStudentSurvey [{$survey->id}]: {$code}");
    }

    /**
     * Генерация уникального 6-значного кода в рамках survey_assignment
     */
    protected function generateUniqueCode(int $assignmentId): string
    {
        do {
            // Генерация случайного 6-значного кода
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (
            AccessTokenStudentSurvey::where('access_code', $code)
            ->where('survey_assignment_id', $assignmentId)
            ->exists()
        );

        return $code;
    }
}
