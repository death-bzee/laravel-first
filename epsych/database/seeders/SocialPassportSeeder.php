<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialPassport;

class SocialPassportSeeder extends Seeder
{
    /**
     * Заполняет таблицу social_passports начальными данными.
     */
    public function run(): void
    {
        SocialPassport::query()->insertOrIgnore([
            // Boolean статусы (да/нет)
            ['title' => json_encode(['ru' => 'Полные семьи'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 1],
            ['title' => json_encode(['ru' => 'Неполные семьи'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 2],
            ['title' => json_encode(['ru' => 'Нет матери'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 3],
            ['title' => json_encode(['ru' => 'Нет отца'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 4],
            ['title' => json_encode(['ru' => 'Многодетные'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 5],
            ['title' => json_encode(['ru' => 'Малообеспеченные'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 6],
            ['title' => json_encode(['ru' => 'Сироты, дети, оставшиеся без попечения родителей'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 7],
            ['title' => json_encode(['ru' => 'Дети с инвалидностью, дети с особыми образовательными потребностями'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 8],
            ['title' => json_encode(['ru' => 'Учащиеся, не имеющие гражданства'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 9],
            ['title' => json_encode(['ru' => 'Учащиеся-граждане другого государства'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 10],
            ['title' => json_encode(['ru' => 'Стоит на учете в отделе по делам несовершеннолетних'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 11],
            ['title' => json_encode(['ru' => 'Стоит на учете внутришкольного контроля'], JSON_UNESCAPED_UNICODE), 'type' => 'boolean', 'active' => true, 'sort' => 12],

            // Образование родителей или других законных представителей учащегося
            ['title' => json_encode(['ru' => 'Образование родителей или других законных представителей учащегося'], JSON_UNESCAPED_UNICODE), 'type' => 'foreignId', 'active' => true, 'sort' => 13],
        ]);
    }
}
