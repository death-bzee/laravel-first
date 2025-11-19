<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Concerns\EducationLevel;

class EducationLevelSeeder extends Seeder
{
    /**
     * Заполняет таблицу education_levels начальными данными.
     */
    public function run(): void
    {
        EducationLevel::query()->insertOrIgnore([
            ['title' => json_encode(['ru' => 'Высшее, послевузовское'], JSON_UNESCAPED_UNICODE), 'active' => true, 'sort' => 1],
            ['title' => json_encode(['ru' => 'Техническое и профессиональное'], JSON_UNESCAPED_UNICODE), 'active' => true, 'sort' => 2],
            ['title' => json_encode(['ru' => 'Среднее'], JSON_UNESCAPED_UNICODE), 'active' => true, 'sort' => 3],
        ]);
    }
}
