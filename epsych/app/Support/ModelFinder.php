<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class ModelFinder
{
    /**
     * Ищет полный путь к классу модели в папке App\Models (включая подпапки).
     *
     * @param string $modelName
     * @return string|null
     */
    public static function find(string $modelName): ?string
    {
        $basePath = app_path('Models');
        $files = File::allFiles($basePath);

        foreach ($files as $file) {
            $relativePath = str_replace(
                [$basePath, '.php', DIRECTORY_SEPARATOR],
                ['', '', '\\'],
                $file->getPathname()
            );

            $className = "App\\Models{$relativePath}";

            if (class_exists($className) && class_basename($className) === $modelName) {
                return $className;
            }
        }

        return null;
    }
}
