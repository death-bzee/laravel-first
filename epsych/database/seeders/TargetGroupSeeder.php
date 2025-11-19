<?php

namespace Database\Seeders;

use App\Models\Concerns\TargetGroup;
use Illuminate\Database\Seeder;

class TargetGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TargetGroup::query()->insert([
            ['title' => json_encode(['ru' => 'Ученики', 'kk' => 'Оқушылар']), 'active' => true, 'sort' => 1],
            ['title' => json_encode(['ru' => 'Учителя', 'kk' => 'Мұғалімдер']), 'active' => true, 'sort' => 2],
            ['title' => json_encode(['ru' => 'Родители', 'kk' => 'Ата-аналар']), 'active' => true, 'sort' => 3],
            ['title' => json_encode(['ru' => 'Администраторы', 'kk' => 'Әкімшілер']), 'active' => true, 'sort' => 4],
            ['title' => json_encode(['ru' => 'Технический персонал', 'kk' => 'Техникалық қызметкерлер']), 'active' => true, 'sort' => 5],
        ]);
    }
}
