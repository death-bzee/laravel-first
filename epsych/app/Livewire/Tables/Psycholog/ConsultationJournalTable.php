<?php

namespace App\Livewire\Tables\Psycholog;

use App\Contracts\User\UserRoleServiceContract;
use App\Enums\RoleEnum;
use App\Models\Classroom;
use App\Models\ConsultationJournal;
use App\Models\Student;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentFields;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ConsultationJournalTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentFields;
    use HasFilamentActions;

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('Нет записей'))
            ->query(app(UserRoleServiceContract::class)->applyUserFilterToQuery(ConsultationJournal::query()))
            ->columns([
                TextColumn::make('date')
                    ->label(__('Дата'))
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('consultable')
                    ->label(__('Консультируемый'))
                    ->formatStateUsing(fn($record) => match ($record->consultable_type) {
                        Classroom::class => $record->consultable?->classroom_full_name ?? __('Нет данных'),

                        Student::class => $record->consultable
                            ? $record->consultable->full_name .
                            ($record->consultable->classroom?->classroom_full_name
                                ? ' (' . $record->consultable->classroom->classroom_full_name . ')'
                                : '')
                            : __('Нет данных'),

                        default => __('Неизвестный тип'),
                    }),
                TextColumn::make('consultant')
                    ->label(__('Консультант'))
                    ->searchable(),
                TextColumn::make('comment')
                    ->label(__('Комментарий'))
                    ->visible(fn($record) => auth()->user()?->hasRole(RoleEnum::StudentAffairsManager))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('Дата создания'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Дата обновления'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                $this->viewAction()
                    ->url(fn($record) => route('consultation-journal-view', $record))
                    ->visible(auth()->user()->hasRole(RoleEnum::Psychologist))
                    ->authorize(fn($record) => auth()->user()?->hasRole(RoleEnum::Psychologist)),
                $this->editAction()
                    ->url(fn($record) => route('consultation-journal-edit', $record))
                    ->visible(auth()->user()->hasRole(RoleEnum::Psychologist))
                    ->authorize(fn($record) => auth()->user()?->hasRole(RoleEnum::Psychologist)),
                $this->deleteAction()
                    ->visible(auth()->user()->hasRole(RoleEnum::Psychologist))
                    ->authorize(fn($record) => auth()->user()?->hasRole(RoleEnum::Psychologist)),
                $this->editCommentAction()
                    ->visible(auth()->user()->hasRole(RoleEnum::StudentAffairsManager))
                    ->authorize(fn($record) => auth()->user()?->hasRole(RoleEnum::StudentAffairsManager)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.psycholog.consultation-journal-table');
    }
}
