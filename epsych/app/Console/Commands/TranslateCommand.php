<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TranslateService;
use App\Jobs\TranslateRecordJob;

class TranslateCommand extends Command
{
    protected $signature = 'translate {model} {jsonField} {source} {target}';
    protected $description = 'Переводит записи в JSON-поле модели с одного языка на другой.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(TranslateService $translateService): void
    {
        $model = $this->argument('model');
        $jsonField = $this->argument('jsonField');
        $source = $this->argument('source');
        $target = $this->argument('target');

        // Получаем ОДНУ запись для перевода
        $records = $translateService->getUntranslatedRecords($model, $jsonField, $target);

        if ($records->isEmpty()) {
            $this->info('Нет записей для перевода.');
            return;
        }

        foreach ($records as $record) {
            // Отправляем в очередь ТОЛЬКО одну запись
            TranslateRecordJob::dispatch($record['model'], $record['id'], $jsonField, $source, $target);
            $this->info("Перевод добавлен в очередь: ID {$record['id']} ({$record['model']})");
        }
    }
}
