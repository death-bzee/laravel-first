<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserPreviousPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param array<string, string> $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => $this->passwordRules(),
        ], [
            'current_password.current_password' => __('Предоставленный пароль не совпадает с вашим текущим паролем.'),
        ])->validateWithBag('updatePassword');

        $this->ensurePasswordDifference($input['current_password'], $input['password']);

        // Проверяем, использовался ли этот пароль раньше
        foreach ($user->previousPasswords as $previousPassword) {
            if (Hash::check($input['password'], $previousPassword->password)) {
                throw ValidationException::withMessages([
                    'password' => __('Вы не можете использовать один из последних 5 паролей.'),
                ]);
            }
        }

        UserPreviousPassword::query()->firstOrCreate([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        // Получаем ID старых паролей, оставляя 5 последних
        $oldPasswordIds = $user->previousPasswords()
            ->orderBy('created_at', 'desc')
            ->get()
            ->skip(5)
            ->pluck('id');

        // Удаляем только старые записи
        UserPreviousPassword::query()->whereIn('id', $oldPasswordIds)->delete();

        // Обновляем пароль пользователя
        $user->forceFill([
            'password' => Hash::make($input['password']),
            'password_changed_at' => now(),
        ])->save();
    }

    /**
     * Ensure the new password differs from the current one by at least 4 characters.
     */
    private function ensurePasswordDifference(string $currentPassword, string $newPassword): void
    {
        $differences = 0;
        $maxLength = max(strlen($currentPassword), strlen($newPassword));

        for ($i = 0; $i < $maxLength; $i++) {
            $charOld = $currentPassword[$i] ?? null;
            $charNew = $newPassword[$i] ?? null;

            if ($charOld !== $charNew) {
                $differences++;
            }
        }

        if ($differences < 4) {
            throw ValidationException::withMessages([
                'password' => __('Новый пароль должен отличаться от текущего хотя бы на 4 символа.'),
            ]);
        }
    }
}
