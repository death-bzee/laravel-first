<?php

namespace App\Livewire\Tables\Psycholog;

use App\Contracts\User\UserRoleServiceContract;
use App\Enums\EventTypeEnum;
use App\Enums\RoleEnum;
use App\Models\ConsultationJournal;
use App\Models\Survey\SurveyGroupAssignment;
use App\Models\WorkPlan;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentColumns;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class WorkPlanTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentActions;
    use HasFilamentColumns;

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('Нет записей'))
            ->query(
                app(UserRoleServiceContract::class)
                    ->applyUserFilterToQuery(WorkPlan::query()->with('workPlanable'))
            )
            ->columns([
                TextColumn::make('item_number')
                    ->label(__('№ п/п'))
                    ->sortable(),
                TextColumn::make('work_planable_type')
                    ->label(__('Мероприятия'))
                    ->formatStateUsing(fn($record) => collect(EventTypeEnum::cases())
                        ->firstWhere(fn(EventTypeEnum $enum) => $enum->getModelClass() === $record->work_planable_type)?->getLabel()
                    ),
                TextColumn::make('activityDirection.title')
                    ->label(__('Направление деятельности'))
                    ->sortable()
                    ->formatStateUsing(fn($record) => $record->activity_direction_other ?: $record->activityDirection?->title),

                TextColumn::make('targetGroup.title')
                    ->label(__('Целевая группа'))
                    ->sortable(),

                TextColumn::make('execution_deadline')
                    ->label(__('Срок исполнения'))
                    ->date('d F Y')
                    ->sortable(),

                TextColumn::make('completion_form')
                    ->label(__('Форма завершения'))
                    ->words(5)
                    ->sortable(),

                TextColumn::make('responsible_person')
                    ->label(__('Ответственные'))
                    ->sortable(),
                TextColumn::make('comment')
                    ->label(__('Комментарий'))
                    ->visible(fn($record) => auth()->user()?->hasRole(RoleEnum::StudentAffairsManager))
                    ->searchable(),

                TextColumn::make('workPlanable')
                    ->label(__('Отметка об исполнении'))
                    ->formatStateUsing(fn($record) => match ($record->work_planable_type) {
                        SurveyGroupAssignment::class => $record->workPlanable?->title ?? __('Нет данных'),
                        ConsultationJournal::class => $record->workPlanable
                            ? "({$record->workPlanable->date}) {$record->workPlanable->student?->fullName}"
                            : __('Нет данных'),
                        default => __('Неизвестный тип'),
                    })
                    ->icon('icon-view-primary')
                    ->color('primary')
                    ->url(fn($record) => match ($record->work_planable_type) {
                        SurveyGroupAssignment::class => $record->work_planable_id ? route('survey-group-assign-view', $record->work_planable_id) : null,
                        ConsultationJournal::class => $record->work_planable_id ? route('consultation-journal-view', $record->work_planable_id) : null,
                        default => null,
                    }),

                ...self::getCreationColumns(),
            ])
            ->filters([
                //
            ])
            ->actions([
                $this->editExecutionPlan()
                    ->visible(function ($record): bool {
                        return auth()->user()->can('update_work::plan')
                            && EventTypeEnum::tryFromModel($record->work_planable_type)?->getModelClass() !== null;
                    })
                    ->authorize(fn($record) => auth()->user()->can('update_work::plan')),
                $this->editAction()
                    ->url(fn($record) => route('work-plan-edit', $record))
                    ->visible(fn() => auth()->user()->can('update_work::plan'))
                    ->authorize(fn($record) => auth()->user()->can('update_work::plan')),
                $this->deleteAction()
                    ->visible(fn() => auth()->user()->can('delete_work::plan'))
                    ->authorize(fn($record) => auth()->user()->can('delete_work::plan')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.psycholog.work-plan-table');
    }
}
