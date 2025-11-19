<?php

namespace App\Traits\Filament;

use App\Enums\EventTypeEnum;
use App\Models\ConsultationJournal;
use App\Models\Survey\SurveyGroupAssignment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;

trait HasFilamentActions
{

    public static function viewAction(): Action
    {
        return Action::make('view')
            ->label(__('Посмотреть'))
            ->icon('icon-view-primary')
            ->extraAttributes(['wire:navigate' => 'true']);
    }

    public static function editAction(): Action
    {
        return Action::make('edit')
            ->label(__('Изменить'))
            ->icon('icon-file-edit')
            ->extraAttributes(['wire:navigate' => 'true']);
    }

    public static function deleteAction(): DeleteAction
    {
        return DeleteAction::make()
            ->modalHeading(fn($record) => __('Удаление записи'));
    }

    public static function editCommentAction(): Action
    {
        return EditAction::make('editComment')
            ->label(__('Комментарий'))
            ->icon('icon-file-edit')
            ->form([
                Textarea::make('comment')
                    ->label(__('Комментарий'))
                    ->rows(5)
                    ->columnSpanFull(),
            ])
            ->action(function ($record, array $data) {
                $record->comment = $data['comment'];
                $record->save();
            })
            ->modalButton(__('Сохранить'))
            ->modalHeading(__('Добавить/Изменить комментарий'));
    }

    public static function editExecutionPlan(): Action
    {
        return EditAction::make('execution_note')
            ->label(__('Отметка об исполнении'))
            ->form([
                Select::make('workPlanable')
                    ->label(__('Выберите значение'))
                    ->options(function ($get, $record) {
                        $modelClass = EventTypeEnum::tryFromModel($record->work_planable_type)?->getModelClass();

                        if (!$modelClass) {
                            return [];
                        }

                        return app($modelClass)::query()
                            ->when($modelClass === ConsultationJournal::class, function ($query) {
                                return $query->where('user_id', auth()->id())
                                    ->get()
                                    ->mapWithKeys(fn($item) => [
                                        $item->id => "({$item->date}) {$item->student?->fullName}",
                                    ]);
                            })
                            ->when($modelClass === SurveyGroupAssignment::class, function ($query) {
                                return $query->where('organization_id', auth()->user()->organization_id)
                                    ->pluck('title', 'id')
                                    ->toArray();
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->afterStateHydrated(function ($set, $record) {
                        if ($record->work_planable_id) {
                            $set('workPlanable', (string)$record->work_planable_id); // Принудительно устанавливаем значение
                        }
                    })
                    ->columnSpanFull(),
            ])
            ->action(function (array $data, $record) {
                $modelClass = EventTypeEnum::tryFromModel($record->work_planable_type)?->getModelClass();

                if (!$modelClass) {
                    throw new \Exception(__('Ошибка: Не удалось определить тип мероприятия'));
                }

                $record->update([
                    'work_planable_type' => $modelClass, // Сохраняем тип
                    'work_planable_id' => $data['workPlanable'], // Сохраняем ID
                ]);
            })
            ->modal()
            ->modalHeading(__('Отметка об исполнении'))
            ->icon('icon-file-edit');
    }
}
