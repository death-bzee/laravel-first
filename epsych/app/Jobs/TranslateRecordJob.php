<?php

namespace App\Jobs;

use App\Contracts\TranslateServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranslateRecordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $model;
    protected int $recordId;
    protected string $jsonField;
    protected string $source;
    protected string $target;

    /**
     * Создает экземпляр задания.
     */
    public function __construct(string $model, int $recordId, string $jsonField, string $source, string $target)
    {
        $this->model = $model;
        $this->recordId = $recordId;
        $this->jsonField = $jsonField;
        $this->source = $source;
        $this->target = $target;
    }

    /**
     * Выполняет задание.
     */
    public function handle(TranslateServiceContract $translateService): void
    {
        $modelClass = $this->model;
        $record = $modelClass::find($this->recordId);

        if (!$record) {
            echo "❌ Запись не найдена: ID {$this->recordId}\n";
            return;
        }

        // Получаем исходный текст
        $sourceText = $record->getTranslation($this->jsonField, $this->source, false);

        if (empty($sourceText)) {
            echo "⚠️ Исходный текст для перевода отсутствует (поле: {$this->jsonField}, ключ: {$this->source})\n";
            return;
        }

        echo "📖 Исходный текст ({$this->source}): {$sourceText}\n";

        // Переводим текст
        $translatedText = $translateService->translateByGPT($sourceText, $this->target);

        echo "💾 Новый перевод ({$this->target}): {$translatedText}\n";

        // Сохраняем перевод через Spatie
        $record->setTranslation($this->jsonField, $this->target, $translatedText);
        $record->save();

        echo "✅ Перевод сохранён в базу!\n";
    }

}
