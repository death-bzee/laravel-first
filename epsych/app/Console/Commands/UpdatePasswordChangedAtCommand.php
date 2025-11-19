<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdatePasswordChangedAtCommand extends Command
{
    protected $signature = 'users:update-password-changed-at';
    protected $description = 'Обновляет поле password_changed_at для всех пользователей, у которых оно отсутствует';

    public function handle(): void
    {
        $count = User::query()->whereNull('password_changed_at')->update([
            'password_changed_at' => now(), // Устанавливаем текущее время
        ]);

        $this->info("Обновлено записей: $count");
    }
}
