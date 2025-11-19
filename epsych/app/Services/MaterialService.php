<?php

namespace App\Services;

use App\Contracts\MaterialServiceContract;

class MaterialService implements MaterialServiceContract
{
    /**
     * Обрабатывает массив видео, добавляя ссылки на превью.
     *
     * @param array $videos
     * @return array
     */
    public function processVideos(array $videos): array
    {
        // Фильтруем и обрабатываем массив, исключая пустые или null значения
        return array_values(array_filter(array_map(function ($video) {
            // Проверяем, что $video является массивом и содержит ключ 'link' с непустым значением
            if (is_array($video) && is_string($video['link']) && !empty($video['link'])) {
                $videoUrl = $video['link'];

                // Извлекаем видео ID из URL (предполагаем, что URL формата "https://www.youtube.com/watch?v=videoId")
                parse_str(parse_url($videoUrl, PHP_URL_QUERY), $query);
                $videoId = $query['v'] ?? null;

                // Если видео ID найдено, формируем ссылку на превью
                if ($videoId) {
                    return [
                        'url' => $videoUrl,
                        'thumbnail' => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
                    ];
                }
            }

            // Возвращаем null, чтобы этот элемент был удален фильтрацией
            return null;
        }, $videos)));
    }


    /**
     * Обрабатывает массив файлов, добавляя расширение каждого файла.
     *
     * @param array $files
     * @return array
     */
    public function processFiles(array $files): array
    {
        return array_map(function ($fileUrl) {
            return [
                'url' => $fileUrl,
                'extension' => pathinfo($fileUrl, PATHINFO_EXTENSION),
                'original_name' => basename($fileUrl), // Возвращает имя файла из URL
            ];
        }, $files);
    }
}
