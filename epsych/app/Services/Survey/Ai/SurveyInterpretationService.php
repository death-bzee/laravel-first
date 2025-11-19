<?php

namespace App\Services\Survey\Ai;

use App\Contracts\TranslateServiceContract;
use App\Services\GptService;

class SurveyInterpretationService
{
    protected GptService $gptService;

    /**
     * Конструктор.
     */
    public function __construct(GptService $gptService)
    {
        $this->gptService = $gptService;
    }

    /**
     * Метод для генерации интерпретации результатов тестирования.
     */
    public function generateInterpretation(array $surveyJson): array
    {
        $interpretation = $this->getInterpretation($surveyJson);
        $translatedInterpretation = $this->translate($interpretation);

        return [
            'interpretation' => [
                'ru' => $interpretation,
                'kk' => $translatedInterpretation,
            ],
        ];
    }

    /**
     * Получает интерпретацию на основе результатов теста.
     */
    private function getInterpretation(array $surveyJson): string
    {
        $prompt = $this->generatePrompt($surveyJson);

        return $this->gptService->sendGptRequest(
            'Ты профессиональный психолог, который анализирует и интерпретирует результаты тестов.',
            $prompt,
            4000,
            0.7,
            'gpt-4o'
        );
    }

    protected function generatePrompt(array $surveyJson): ?string
    {
        if (
            empty($surveyJson['title']) ||
            empty($surveyJson['description']) ||
            empty($surveyJson['interpretation_prompt']) ||
            empty($surveyJson['scaling'])
        ) {
            return null;
        }

        $data = [
            'title' => $surveyJson['title'],
            'description' => $surveyJson['description'],
            'scaling_results' => $surveyJson['scaling'],
            'interpretation_prompt' => $surveyJson['interpretation_prompt'],
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function translate(string $text): string
    {
        return app(TranslateServiceContract::class)->translateByGPT($text, 'Kazakh');
    }
}
