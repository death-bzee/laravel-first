<?php

namespace Database\Seeders;

use App\Models\Concerns\Nationality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalities = [
            ['title' => ['ru' => 'Казах', 'kk' => 'Қазақ']],
            ['title' => ['ru' => 'Русский', 'kk' => 'Орыс']],
            ['title' => ['ru' => 'Узбек', 'kk' => 'Өзбек']],
            ['title' => ['ru' => 'Украинец', 'kk' => 'Украин']],
            ['title' => ['ru' => 'Татарин', 'kk' => 'Татар']],
            ['title' => ['ru' => 'Немец', 'kk' => 'Неміс']],
            ['title' => ['ru' => 'Киргиз', 'kk' => 'Қырғыз']],
            ['title' => ['ru' => 'Таджик', 'kk' => 'Тәжік']],
            ['title' => ['ru' => 'Туркмен', 'kk' => 'Түрікмен']],
            ['title' => ['ru' => 'Уйгур', 'kk' => 'Ұйғыр']],
            ['title' => ['ru' => 'Другая', 'kk' => 'Басқа']],
            ['title' => ['ru' => 'Американец', 'kk' => 'Америкалық']],
            ['title' => ['ru' => 'Китаец', 'kk' => 'Қытай']],
            ['title' => ['ru' => 'Индиец', 'kk' => 'Үнді']],
            ['title' => ['ru' => 'Кореец', 'kk' => 'Корей']],
            ['title' => ['ru' => 'Японец', 'kk' => 'Жапон']],
            ['title' => ['ru' => 'Француз', 'kk' => 'Француз']],
            ['title' => ['ru' => 'Итальянец', 'kk' => 'Италиялық']],
            ['title' => ['ru' => 'Испанец', 'kk' => 'Испандық']],
            ['title' => ['ru' => 'Араб', 'kk' => 'Араб']],
            ['title' => ['ru' => 'Перс', 'kk' => 'Парсы']],
        ];

        foreach ($nationalities as $nationality) {
            Nationality::query()->create($nationality);
        }
    }
}
