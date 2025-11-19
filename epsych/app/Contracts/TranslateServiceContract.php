<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface TranslateServiceContract
{
    /**
     * Получает коллекцию записей, где указанный JSON-ключ отсутствует или пуст.
     *
     * @param string $modelName Название модели
     * @param string $jsonField Название JSON-поля в модели
     * @param string $target
     * @return Collection
     */
    public function getUntranslatedRecords(string $modelName, string $jsonField, string $target): Collection;

    /**
     * Переводит текст на указанный язык.
     *
     * @param string $text
     * @param string $language
     * @param string $model
     * @return string
     */
    public function translateByGPT(string $text, string $language, string $model = 'gpt-4o'): string;
}
