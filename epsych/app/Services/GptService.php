<?php

namespace App\Services;

use OpenAI;
use OpenAI\Client;
use OpenAI\Responses\Chat\CreateResponse;

class GptService
{
    protected Client $client;

    public function __construct()
    {
        // Получаем API-ключ из конфигурации
        $apiKey = config('services.openai.api_key');

        // Инициализируем клиент OpenAI с использованием ключа из конфигурации
        $this->client = OpenAI::client($apiKey);
    }

    public function sendGptRequest(string $systemMessage, string $userMessage, int $maxTokens = 500, float $temperature = 0, string $model = 'gpt-4o-mini'): string
    {
        // Отправляем запрос в GPT-4
        $response = $this->client->chat()->create([
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemMessage,
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ]);

        // Возвращаем результат или сообщение об ошибке
        return $response->choices[0]->message->content ?? __('Ошибка генерации ответа');
    }

    public function sendGptToolRequest(
        string $functionName,
        string $functionDescription,
        array $parametersSchema,
        string $systemMessage,
        string $userMessage,
        string $model = 'gpt-4o',
        float $temperature = 0.0
    ): CreateResponse {
        return $this->client->chat()->create([
            'model' => $model,
            'temperature' => $temperature,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemMessage,
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ],
            'tools' => [
                [
                    'type' => 'function',
                    'function' => [
                        'name' => $functionName,
                        'description' => $functionDescription,
                        'parameters' => $parametersSchema,
                    ],
                ],
            ],
            'tool_choice' => [
                'type' => 'function',
                'function' => ['name' => $functionName],
            ],
        ]);
    }
}
