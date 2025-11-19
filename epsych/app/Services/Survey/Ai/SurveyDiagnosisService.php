<?php

namespace App\Services\Survey\Ai;

use App\Contracts\TranslateServiceContract;
use App\Services\GptService;

class SurveyDiagnosisService
{
    protected GptService $gptService;

    public function __construct(GptService $gptService)
    {
        $this->gptService = $gptService;
    }

    /**
     * Метод для генерации ответа на результаты тестирования.
     */
    public function generateStudentDiagnosis(array $surveyJson): array
    {
        $diagnosis = $this->getDiagnosis($surveyJson);
        $translatedDiagnosis = $this->translate($diagnosis);

        $standardDiagnosis = [
            'ru' => $diagnosis,
            'kk' => $translatedDiagnosis,
        ];

        $explainedDiagnosis = $this->getExplainedDiagnosis($surveyJson, $diagnosis);
        $translatedExplainedDiagnosis = $this->translate($explainedDiagnosis);

        $detailedDiagnosis = [
            'ru' => $explainedDiagnosis,
            'kk' => $translatedExplainedDiagnosis,
        ];

        return [
            'diagnosis' => $standardDiagnosis,
            'explained_diagnosis' => $detailedDiagnosis,
        ];
    }

    private function getDiagnosis(array $surveyJson): string
    {
        $prompt = $this->generatePrompt($surveyJson);

        logger()->info('surveyDiagnosisService.getDiagnosis', ['prompt' => $prompt]);

        return $this->gptService->sendGptRequest(
            'You are a helpful assistant that interprets survey results based on given data.',
            $prompt,
            4000,
            0.0,
            'gpt-4o'
        );
    }

    private function getExplainedDiagnosis(array $surveyJson, string $diagnosis): string
    {
        $prompt = 'Тест проводился среди учеников школы (не взрослых). На основе теста "'.$surveyJson['title'].'" и выявленного диагноза: "'.$diagnosis.'". Пожалуйста, предоставьте развернутое психологическое объяснение данного диагноза. Форматируйте текст, используя только теги HTML: <strong>, <ul>, <ol> и <p>. Не используйте другие теги.';

        return $this->gptService->sendGptRequest(
            'You are a professional psychologist who provides a concise and professional explanation of the results based on the data.',
            $prompt,
            4096,
            0.7
        );
    }

    /**
     * Генерация промпта на основе переданных данных.
     */
    protected function generatePrompt(array $surveyJson): ?string
    {
        $title = $surveyJson['title'] ?? null;
        $description = $surveyJson['description'] ?? null;
        $questions = $surveyJson['questions'] ?? null;
        $interpretation = $surveyJson['interpretation'] ?? null;

        $data = [
            'title' => $title,
            'description' => $description,
            'questions' => $questions,
            'interpretation' => $interpretation,
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    private function translate(string $text): string
    {
        return app(TranslateServiceContract::class)->translateByGPT($text, 'Kazakh');
    }
}
