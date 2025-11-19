<?php

namespace Database\Seeders;

use App\Enums\LevelGroupTypeEnum;
use App\Models\LevelGroup;
use App\Models\LevelValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelGroupAndValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Группы риска
        $groups = [
            [
                'title' => ['ru' => 'Группа риска для тревожности', 'kk' => 'Қауіп тобы (мазасыздық)'],
                'type' => LevelGroupTypeEnum::Risk,
                'values' => [
                    [
                        'value' => 1,
                        'title' => ['ru' => 'Низкий уровень тревожности', 'kk' => 'Төмен мазасыздық деңгейі'],
                    ],
                    [
                        'value' => 2,
                        'title' => ['ru' => 'Средний уровень тревожности', 'kk' => 'Орташа мазасыздық деңгейі'],
                    ],
                    [
                        'value' => 3,
                        'title' => ['ru' => 'Высокий уровень тревожности', 'kk' => 'Жоғары мазасыздық деңгейі'],
                    ],
                ],
            ],
            [
                'title' => ['ru' => 'Группа риска для мотивации', 'kk' => 'Қауіп тобы (мотивация)'],
                'type' => LevelGroupTypeEnum::Motivation,
                'values' => [
                    [
                        'value' => 1,
                        'title' => ['ru' => 'Низкая мотивация', 'kk' => 'Төмен мотивация'],
                    ],
                    [
                        'value' => 2,
                        'title' => ['ru' => 'Средняя мотивация', 'kk' => 'Орташа мотивация'],
                    ],
                    [
                        'value' => 3,
                        'title' => ['ru' => 'Высокая мотивация', 'kk' => 'Жоғары мотивация'],
                    ],
                ],
            ],
        ];

        foreach ($groups as $groupData) {
            // Создаём группу
            $group = LevelGroup::query()->create([
                'title' => $groupData['title'],
                'type' => $groupData['type'],
            ]);

            // Добавляем значения уровней
            foreach ($groupData['values'] as $valueData) {
                LevelValue::query()->create([
                    'code' => '',
                    'value' => $valueData['value'],
                    'title' => $valueData['title'],
                    'level_group_id' => $group->id,
                ]);
            }
        }
    }
}
