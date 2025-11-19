<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Заполняет таблицу menus начальными данными.
     */
    public function run(): void
    {
        Menu::query()->insert([
            [
                'is_active' => false,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Дашборд']),
                'link' => 'dashboard',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                '_blank' => false,
                'title' => json_encode(['ru' => 'План работы']),
                'link' => 'work-plans',
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Журнал учета']),
                'link' => 'consultation-journals',
                'sort' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Классы']),
                'link' => 'classrooms',
                'sort' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Ученики']),
                'link' => 'students',
                'sort' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Расписание мероприятий']),
                'link' => 'events',
                'sort' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Тестирование']),
                'link' => 'survey-group-assign',
                'sort' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Ученики и тесты']),
                'link' => 'survey-assign',
                'sort' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => false,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Результаты тестирования']),
                'link' => 'results',
                'sort' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                '_blank' => false,
                'title' => json_encode(['ru' => 'Ресурсные материалы']),
                'link' => '#',
                'sort' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
