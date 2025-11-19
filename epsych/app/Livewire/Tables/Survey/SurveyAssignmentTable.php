<?php

namespace App\Livewire\Tables\Survey;

use App\Contracts\User\UserRoleServiceContract;
use App\Filament\Resources\Survey\SurveyAssignmentResource\Actions\ExportSurveyAssignmentAction;
use App\Filament\Resources\Survey\SurveyAssignmentResource\Filters\SurveyAssignmentFilter;
use App\Models\Survey\SurveyAssignment;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentColumns;
use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class SurveyAssignmentTable extends Component implements HasForms, HasTable
{
    use HasFilamentActions;
    use HasFilamentColumns;
    use InteractsWithForms;
    use InteractsWithTable;

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        $userService = app(UserRoleServiceContract::class);
        $organizationIds = $userService->getOrganizationsByUser();

        return $table
            ->defaultSort('completed_at', 'desc')
            ->emptyStateHeading(__('Нет записей'))
            ->query(
                SurveyAssignment::query()
                    ->whereHas('group', function (Builder $query) use ($organizationIds) {
                        $query->whereIn('organization_id', $organizationIds);
                    })
                    ->with(['groupAssignment', 'student', 'studentDiagnosis'])
            )
            ->columns([

                TextColumn::make('groupAssignment.title')
                    ->label(__('Группа'))
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->groupAssignment?->survey?->title)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('groupAssignment.survey.title')
                    ->label(__('Тест'))
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->groupAssignment?->survey?->title ?? null),

                TextColumn::make('groupAssignment.organization.bin')
                    ->label(__('Организация'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('groupAssignment.classroom.grade')
                    ->label(__('Класс'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('groupAssignment.classroom.letter')
                    ->label(__('Литера'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.surname')
                    ->label(__('Фамилия'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.name')
                    ->label(__('Имя'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.patronymic')
                    ->label(__('Отчество'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('assigned_at')
                    ->label(__('Назначен'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('started_at')
                    ->label(__('Запущен'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('completed_at')
                    ->label(__('Завершен'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),

                ...self::getCreationColumns(),
            ])
            ->filters(SurveyAssignmentFilter::make())
            ->actions([
                Action::make('results')
                    ->label(__('Результаты'))
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    ->url(fn($record) => route('results.view', ['id' => $record->id]))
                    ->extraAttributes([
                        'wire:navigate' => true,
                    ])
                    ->visible(fn($record) => $record->completed_at && $record->studentDiagnosis),

                $this->deleteAction()
                    ->visible(fn() => auth()->user()->can('delete_survey::survey::assignment'))
                    ->authorize(fn($record) => auth()->user()->can('delete_survey::survey::assignment', $record)),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->visible(fn() => auth()->user()->can('delete_survey::survey::assignment'))
                    ->authorize(fn() => auth()->user()->can('delete_survey::survey::assignment')),
                ExportSurveyAssignmentAction::make()
                    ->visible(fn() => auth()->user()->can('view_any_survey::survey::assignment'))
                    ->authorize(fn() => auth()->user()->can('view_any_survey::survey::assignment')),
            ])
            ->defaultPaginationPageOption(20) // по умолчанию 10 строк на странице
            ->paginated([5, 10, 25, 50, 100]);
    }

    public function render(): View
    {
        return view('livewire.tables.survey.survey-assignment-table');
    }
}
