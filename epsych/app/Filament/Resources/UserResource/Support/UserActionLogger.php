<?php

namespace App\Filament\Resources\UserResource\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserActionLogger
{
    public static function logCreated(User $user): void
    {
        self::log($user, 'Создание пользователя (:user, :org)');
    }

    public static function logUpdated(User $user): void
    {
        self::log($user, 'Обновление пользователя (:user, :org)');
    }

    public static function logDeleted(User $user): void
    {
        self::log($user, 'Удаление пользователя (:user, :org)');
    }

    public static function logBlocked(User $user): void
    {
        self::log($user, 'Пользователь (:user, :org) был заблокирован');
    }

    public static function logUnblocked(User $user): void
    {
        self::log($user, 'Пользователь (:user, :org) был разблокирован');
    }

    public static function logPasswordChanged(User $user): void
    {
        self::log($user, 'Смена пароля пользователя (:user, :org)');
    }

    public static function logRoleChanged(User $user, array $from, array $to): void
    {
        self::log(
            $user,
            'Смена роли пользователя (:user, :org) с ":from" на ":to"',
            [
                'from' => implode(', ', $from),
                'to' => implode(', ', $to),
            ]
        );
    }

    private static function log(User $user, string $message, array $extra = []): void
    {
        $orgTitle = optional($user->organization)->title;

        $finalMessage = 'admin_actions' . ':' . __($message, array_merge([
            'user' => $user->fullName,
            'org' => $orgTitle,
        ], $extra));

        activity('admin_actions')
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties([
                'target_user' => [
                    'id' => $user->id,
                    'full_name' => $user->fullName,
                    'email' => $user->email,
                    'organization' => $orgTitle,
                ],
                'ip' => request()->ip(),
            ])
            ->log($finalMessage);
    }

}
