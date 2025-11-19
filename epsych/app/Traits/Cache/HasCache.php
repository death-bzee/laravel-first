<?php

namespace App\Traits\Cache;

use Illuminate\Support\Facades\Cache;

trait HasCache
{
    // Общая логика для установки событий Eloquent
    public static function bootHasCache(): void
    {
        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }

    // Метод для очистки кэша
    public static function clearCache(): void
    {
        Cache::forget(static::getCacheKey());
    }

    // Метод для получения ключа кэша
    public static function getCacheKey(): string
    {
        return static::class . '_cache';
    }

    // Метод для кэширования данных
    public static function cacheData($callback, $minutes = 60)
    {
        return Cache::remember(static::getCacheKey(), $minutes, $callback);
    }
}
