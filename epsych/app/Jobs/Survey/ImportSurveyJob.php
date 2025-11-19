<?php

namespace App\Jobs\Survey;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyQuestionOption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class ImportSurveyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        if (! File::exists($this->filePath)) {
            return;
        }

        $json = File::get($this->filePath);
        $data = json_decode($json, true);

        if (! $data) {
            return;
        }

        // Create the survey
        $survey = Survey::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'interpretation' => json_encode($data['interpretation'], JSON_UNESCAPED_UNICODE),
        ]);

        // Create questions and options
        foreach ($data['questions'] as $questionData) {
            $question = SurveyQuestion::create([
                'survey_id' => $survey->id,
                'number' => $questionData['number'],
                'title' => $questionData['title'],
                'type' => $data['type'] ?? 'single_choice', // Используем общий type из JSON
                'limited_multiple_choice' => $data['limit'] ?? null, // Сохраняем лимит в поле limited_multiple_choice
            ]);

            foreach ($questionData['options'] as $optionData) {
                SurveyQuestionOption::create([
                    'question_id' => $question->id,
                    'title' => $optionData['title'],
                    'score' => $optionData['score'],
                ]);
            }
        }
    }
}
