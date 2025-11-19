<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Проверяем, есть ли роль 'super_admin', если нет — создаем
        $role = Role::query()->firstOrCreate(['name' => 'super_admin']);

        // Создаем пользователя с указанными данными
        $user = User::query()->create([
            'name' => 'Andrey',
            'surname' => 'Nikiforov',
            'patronymic' => 'Nikiforov',
            'email' => 'eokz@bk.ru',
            'password' => Hash::make('Aa123456'), // Шифруем пароль
        ]);

        // Назначаем пользователю роль 'super_admin'
        $user->assignRole($role);
    }
}
