<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, Rule|array|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::min(12) // Минимум 12 символов
                ->letters() // Должны быть буквы
                ->mixedCase() // Верхний и нижний регистр
                ->numbers() // Должны быть цифры
                ->symbols() // Должны быть спец. символы
                ->uncompromised(), // Проверка на утечки паролей
            'confirmed', // Подтверждение пароля
        ];
    }
}
