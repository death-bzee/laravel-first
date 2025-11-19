<?php

namespace App\Jobs\Survey;

use App\Models\AccessToken;
use App\Models\Concerns\Relation\AccessTokenStudentSurvey;
use App\Models\Survey\SurveyAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessSurveyAssignmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $surveyAssignmentId = 0; // 👈 инициализация
    /**
     * Create a new job instance.
     *
     * @param SurveyAssignment $surveyAssignment
     */
    public function __construct(int $surveyAssignmentId)
    {
        $this->surveyAssignmentId = $surveyAssignmentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        if ($this->surveyAssignmentId === 0) {
            Log::error("ProcessSurveyAssignmentJob: surveyAssignmentId не передан");
            return;
        }

        $surveyAssignment = SurveyAssignment::find($this->surveyAssignmentId);

        if (! $surveyAssignment) {
            Log::warning("SurveyAssignment [{$this->surveyAssignmentId}] не найден, job пропущен.");
            return;
        }

        $existingSurvey = AccessTokenStudentSurvey::where('survey_assignment_id', $surveyAssignment->id)->first();

        if ($existingSurvey) {
            if (empty($existingSurvey->access_code)) {
                GenerateAccessCodeJob::dispatch($existingSurvey->id);
            }
            return;
        }

        // Генерация случайного токена и создание записи в access_tokens
        $token = AccessToken::create(['token' => Str::random(16)]);
        // Создание записи в access_token_student_surveys
        $survey = AccessTokenStudentSurvey::create([
            'access_token_id'      => $token->id,
            'survey_assignment_id' => $surveyAssignment->id,
            'access_code'          => '',  // Оставляем пустым, чтобы позже сгенерировать через Job
        ]);

        // Запускаем задачу генерации кода
        GenerateAccessCodeJob::dispatch($survey->id);
    }
}
