<?php

namespace App\Console\Commands\Survey;

use Illuminate\Console\Command;

class SurveyTranslateCommand extends Command
{
    protected $signature = 'surveys:translate';
    protected $description = 'Запускает перевод для Survey, SurveyQuestion и SurveyQuestionOption с ru на kk.';

    public function handle(): void
    {
        $this->call('translate', [
            'model' => 'Survey',
            'jsonField' => 'title',
            'source' => 'ru',
            'target' => 'kk',
        ]);

        $this->call('translate', [
            'model' => 'Survey',
            'jsonField' => 'description',
            'source' => 'ru',
            'target' => 'kk',
        ]);

        $this->call('translate', [
            'model' => 'SurveyQuestion',
            'jsonField' => 'title',
            'source' => 'ru',
            'target' => 'kk',
        ]);

        $this->call('translate', [
            'model' => 'SurveyQuestionOption',
            'jsonField' => 'title',
            'source' => 'ru',
            'target' => 'kk',
        ]);

        $this->info('Все задания на перевод добавлены в очередь.');
    }
}
