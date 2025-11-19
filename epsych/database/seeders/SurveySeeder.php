<?php

namespace Database\Seeders;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use App\Models\Survey\SurveyQuestionOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем опрос
        $survey = Survey::create([
            'title' => json_encode(['ru' => 'Опрос по тревожности']),
            'description' => json_encode(['ru' => 'Этот тест поможет определить уровень тревожности у школьников.']),
            'images' => null,
            'interpretation' => json_encode([
                "Подсчет баллов" => "Начисляется по 1 баллу за каждый ответ «да» на вопросы - 1,4,5,6,9,10,12,13,14,15 и «нет» на вопросы -2,3,7,11.",
                "Оценка уровня тревожности" => [
                    "I" => [
                        "4 балла" => "низкий уровень",
                        "5 - 7 баллов" => "средний уровень",
                        "8 - 10 баллов" => "повышенный уровень"
                    ],
                    "II" => [
                        "15 баллов" => "высокий уровень"
                    ]
                ]
            ]),
        ]);

        // Вопросы и варианты ответов
        $questions = [
            'Я быстро устаю',
            'Думаю, что у меня дела лучше, чем у других ребят',
            'Я чувствую себя свободнее',
            'У меня появились головокружения, слабость, подташнивание',
            'Учителя недовольны мной (больше замечаний)',
            'Мне не хватает уверенности в себе',
            'Я чувствую себя в безопасности',
            'Я избегаю трудностей',
            'Я могу легко расстроиться и даже заплакать',
            'У меня стало больше конфликтов',
            'Домашние задания стали интереснее',
            'Я хуже понимаю объяснения учителей',
            'Я долго переживаю неприятности',
            'Я не высыпаюсь',
            'Хочу, чтобы в 5-м классе учили прошлогодние учителя'
        ];

        // Создаем вопросы и варианты ответов
        foreach ($questions as $index => $questionText) {
            $question = SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question_text' => json_encode(['ru' => $questionText]),
                'type' => 'single_choice', // Предполагается, что все вопросы имеют тип одиночного выбора
                'order' => $index + 1, // Установка порядкового номера
            ]);

            // Создаем варианты ответа для каждого вопроса
            SurveyQuestionOption::create([
                'question_id' => $question->id,
                'title' => json_encode(['ru' => 'Да']),
                'score' => null,
            ]);

            SurveyQuestionOption::create([
                'question_id' => $question->id,
                'title' => json_encode(['ru' => 'Нет']),
                'score' => null,
            ]);
        }
    }
}
