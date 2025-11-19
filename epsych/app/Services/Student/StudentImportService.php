<?php

namespace App\Services\Student;

use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class StudentImportService
{
    /**
     * Валидирует и ставит импорт в очередь.
     *
     * @throws ValidationException
     * @throws Throwable
     */
    public function import(string $relativePath, ?int $organizationId = null): void
    {
        $fullPath = Storage::disk('public')->path($relativePath);

        if (! file_exists($fullPath)) {
            throw new \RuntimeException('Файл не найден: '.$fullPath);
        }

        app(StudentImportValidatorService::class)->validate($fullPath, $organizationId);

        Excel::queueImport(new StudentsImport($organizationId), $fullPath);
    }
}
