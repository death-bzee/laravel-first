<?php

namespace App\Services\Survey\Ai;

use App\Contracts\TranslateServiceContract;
use App\Data\Survey\Scaling\ScalingResultItemData;
use App\Repositories\Survey\SurveyScalingDiagnosisRepository;
use App\Services\GptService;
use JsonException;

class SurveyScalingService
{
    public function __construct(
        protected GptService $gptService,
        protected TranslateServiceContract $translateService,
        protected SurveyScalingDiagnosisRepository $surveyDiagnosisRepository,
    ) {}

    /**
     * Рассчитывает шкалирование теста.
     *
     * @throws JsonException
     */
    public function calculateScaling(array $surveyJson): array
    {
        $scalingResult = $this->getScalingResults($surveyJson);

        $translatedScalingResult = $this->translate(
            json_encode($scalingResult, JSON_UNESCAPED_UNICODE)
        );

        return [
            'ru' => $scalingResult,
            'kk' => json_decode($translatedScalingResult, true),
        ];
    }

    /**
     * Переводит текст с русского на казахский.
     */
    private function translate(string $text): string
    {
        return app(TranslateServiceContract::class)->translateByGPT($text, 'Kazakh');
    }

    /**
     * Получает шкалирование на основе тестовых данных.
     *
     * @return ScalingResultItemData[]
     *
     * @throws JsonException
     */
    public function getScalingResults(array $surveyJson): array
    {
        $response = $this->gptService->sendGptToolRequest(
            functionName: 'return_scaling',
            functionDescription: 'Структурированный результат шкалирования по психодиагностике',
            parametersSchema: [
                'type' => 'object',
                'properties' => [
                    'result' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'isGeneral' => ['type' => 'boolean'],
                                'isRisk' => ['type' => 'boolean'],
                                'scaleName' => ['type' => 'string'],
                                'levelName' => ['type' => 'string'],
                                'score' => ['type' => 'integer'],
                            ],
                            'required' => ['isGeneral', 'isRisk', 'scaleName', 'levelName', 'score'],
                        ],
                    ],
                ],
                'required' => ['result'],
            ],
            systemMessage: 'Ты профессиональный психолог, анализирующий тестовые данные.',
            userMessage: json_encode($surveyJson, JSON_UNESCAPED_UNICODE),
        );

        $toolCalls = $response->choices[0]->message->toolCalls ?? [];

        if (empty($toolCalls)) {
            throw new \RuntimeException('GPT не вернул tool_call');
        }

        $argumentsJson = $toolCalls[0]->function->arguments ?? '{}';

        $parsed = json_decode($argumentsJson, true, 512, JSON_THROW_ON_ERROR);

        $result = $parsed['result'] ?? [];

        return ScalingResultItemData::collect($result);
    }
}
