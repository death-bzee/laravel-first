<?php

namespace App\Contracts;

interface GptServiceContract
{
    public function sendGptRequest(string $systemMessage, string $userMessage, int $maxTokens = 500, float $temperature = 0, string $model = 'gpt-4o-mini'): string;
}
