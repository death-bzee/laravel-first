<?php

namespace App\Console\Commands\Survey;

use App\Jobs\Survey\ImportSurveyJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SurveyImportCommand extends Command
{
    protected $signature = 'surveys:import';

    protected $description = 'Scan and import all JSON surveys from the import folder';

    public function handle(): int
    {
        $directory = public_path('storage/surveys/import');

        // Проверяем, существует ли директория
        if (! File::exists($directory)) {
            $this->error("Directory not found: {$directory}");

            return 1;
        }

        // Получаем только JSON-файлы
        $files = collect(File::files($directory))
            ->filter(fn ($file) => $file->getExtension() === 'json'); // Оставляем только .json

        // Если нет JSON-файлов
        if ($files->isEmpty()) {
            $this->info("No JSON files found in: {$directory}");

            return 0;
        }

        // Отправляем файлы в очередь
        foreach ($files as $file) {
            ImportSurveyJob::dispatch($file->getPathname());
        }

        $this->info('Queued '.$files->count().' JSON survey files for import.');

        return 0;
    }
}
