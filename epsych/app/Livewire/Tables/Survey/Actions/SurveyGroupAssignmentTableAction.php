<?php

namespace App\Livewire\Tables\Survey\Actions;

use App\Enums\Survey\SurveyGroupAssignmentStatusEnum;
use App\Models\Survey\SurveyGroupAssignment;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class SurveyGroupAssignmentTableAction
{
    public static function start(): Action
    {
        return Action::make('start')
            ->label(__('Запустить'))
            ->icon('heroicon-s-play')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (SurveyGroupAssignment $record): void {
                $record->update([
                    'status' => SurveyGroupAssignmentStatusEnum::Started,
                    'started_at' => Carbon::now(),
                ]);
            })
            ->visible(function (SurveyGroupAssignment $record): bool {
                return $record->status !== SurveyGroupAssignmentStatusEnum::Started &&
                    $record->status !== SurveyGroupAssignmentStatusEnum::Completed;
            });
    }

    public static function codes(): Action
    {
        return Action::make('codes')
            ->label(__('Коды доступа'))
            ->icon('heroicon-s-key')
            ->color('primary')
            ->url(fn(SurveyGroupAssignment $record) => route('survey-group-assign-codes', ['id' => $record->id]))
            ->openUrlInNewTab();
    }

    public static function shortCode(): Action
    {
        return \Filament\Tables\Actions\Action::make('shortCode')
            ->label(__('Код доступа'))
            ->icon('heroicon-s-lock-closed')
            ->color('warning')
            ->modalHeading(__('Код доступа'))
            ->modalContent(
                fn($record) =>
                view('livewire.components.survey.short-code-modal', [
                    'code' => self::getShortCode($record),
                ])
            )
            ->modalSubmitAction(false)   // убрать "Отправить"
            ->modalCancelAction(false);  // убрать "Отмена"
    }

    /**
     * Получает короткий код из UUID QR кода (последние 6 символов)
     */
    private static function getShortCode($record): string
    {
        $uuid = app(\App\Contracts\QrCodeServiceContract::class)->getUuidQrCode($record);

        if (!$uuid) {
            return 'N/A';
        }

        // Возвращаем последние 6 символов UUID в верхнем регистре
        return strtoupper(substr($uuid, -6));
    }



    public static function generateQrImage(): Action
    {
        return Action::make('qr')
            ->label(__('QR код'))
            ->icon('qr-primary')
            ->color('primary')
            ->dispatch("generateQRImage", fn(SurveyGroupAssignment $record) => ['id' => $record->id])
            ->visible(fn(SurveyGroupAssignment $record) => auth()->user()->can('update', $record));
    }
}
