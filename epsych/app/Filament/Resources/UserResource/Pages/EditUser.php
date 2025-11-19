<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Support\UserActionLogger;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Throwable;

class EditUser extends EditRecord
{

    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(function (User $record) {
                    UserActionLogger::logDeleted($record);
                    $record->delete();
                    $this->redirect($this->getRedirectUrl());
                }),
        ];
    }

    /**
     * @throws Throwable
     */
    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        /** @var User $user */
        $user = $this->record;

        // сохраняем исходные значения вручную
        $originalIsActive = $user->is_active;
        $originalRoles = $user->roles()->pluck('name')->sort()->values()->toArray();

        parent::save($shouldRedirect, $shouldSendSavedNotification);

        $user->refresh();
        $newRoles = $user->roles->pluck('name')->sort()->values()->toArray();

        if ($originalIsActive && !$user->is_active) {
            UserActionLogger::logBlocked($user);
        }

        if (!$originalIsActive && $user->is_active) {
            UserActionLogger::logUnblocked($user);
        }

        if (!empty($this->data['password'] ?? null)) {
            UserActionLogger::logPasswordChanged($user);
        }

        if ($originalRoles !== $newRoles) {
            UserActionLogger::logRoleChanged($user, $originalRoles, $newRoles);
        }

        UserActionLogger::logUpdated($user);
    }
}
