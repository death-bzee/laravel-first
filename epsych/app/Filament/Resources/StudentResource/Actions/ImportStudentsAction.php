<?php

namespace App\Filament\Resources\StudentResource\Actions;

use Filament\Forms\Components\FileUpload;
use App\Services\Student\StudentImportService;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Validation\ValidationException;
use Throwable;

class ImportStudentsAction
{
    public static function make(?int $organizationId = null): Action
    {
        return Action::make('import')
            ->label('Импортировать')
            ->icon('icon-file-excel')
            ->color('success')
            ->form([
                FileUpload::make('students_file')
                    ->label('Файл Excel')
                    ->required()
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]),
            ])
            ->action(function (array $data) use ($organizationId) {
                $file = $data['students_file'];

                try {
                    app(StudentImportService::class)->import($file, $organizationId);

                    Notification::make('admin_actions')
                        ->title('Импорт запущен')
                        ->success()
                        ->send();

                    activity('admin_actions')
                        ->causedBy(auth()->user())
                        ->withProperties(['file' => $file])
                        ->log('Импорт студентов из Excel');

                } catch (ValidationException $e) {
                    Notification::make()
                        ->title('Ошибка валидации')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                } catch (Throwable $e) {
                    Notification::make()
                        ->title('Ошибка')
                        ->body('Ошибка: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
