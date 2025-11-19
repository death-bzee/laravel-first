<?php

namespace Database\Seeders;

use App\Models\Concerns\ActivityDirection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityDirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivityDirection::query()->insert([
            ['title' => json_encode(['ru' => 'Спорт', 'kk' => 'Спорт']), 'active' => true, 'sort' => 1],
            ['title' => json_encode(['ru' => 'Музыка', 'kk' => 'Әуен']), 'active' => true, 'sort' => 2],
            ['title' => json_encode(['ru' => 'Искусство', 'kk' => 'Өнер']), 'active' => true, 'sort' => 3],
            ['title' => json_encode(['ru' => 'Наука', 'kk' => 'Ғылым']), 'active' => true, 'sort' => 4],
            ['title' => json_encode(['ru' => 'Технологии', 'kk' => 'Технологиялар']), 'active' => true, 'sort' => 5],
        ]);
    }
}
