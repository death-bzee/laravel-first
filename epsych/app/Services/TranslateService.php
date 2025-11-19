<?php

namespace App\Services;

use App\Support\ModelFinder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\TranslateServiceContract;

class TranslateService implements TranslateServiceContract
{
    protected GptService $gptService;

    public function __construct(GptService $gptService)
    {
        $this->gptService = $gptService;
    }

    public function translateByGPT(string $text, string $language, string $model = 'gpt-4o'): string
    {
        return $this->gptService->sendGptRequest(
            "You are a professional translator. Your only task is to translate text into {$language}. Do not explain, do not provide additional information, just return the translated text.",
            $text,
            1000,
            '0.3',
            $model
        );
    }

    public function getUntranslatedRecords(string $modelName, string $jsonField, string $target): Collection
    {
        $modelClass = ModelFinder::find($modelName);

        if (!$modelClass || !is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException("Model '{$modelName}' not found or is not a valid Eloquent model.");
        }

        return $modelClass::query()
            ->get(['id', $jsonField])
            ->map(function ($record) use ($modelClass, $jsonField, $target) {
                // Получаем все переводы через Spatie
                $translations = $record->getTranslations($jsonField);

                // Проверяем, есть ли `kk` (целевой язык)
                if (!empty($translations[$target])) {
                    return null; // Пропускаем, если перевод уже есть
                }

                return [
                    'id' => $record->id,
                    'model' => $modelClass, // Полный путь к модели
                    'field' => $translations, // Берём все переводы
                    'json_field' => $jsonField, // Название JSON-поля
                ];
            })
            ->filter(); // Убираем `null` значения (уже переведённые записи)
    }
}
