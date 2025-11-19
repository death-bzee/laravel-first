<?php

namespace App\Livewire\Tables\Student;

use App\Contracts\Student\ClassroomServiceContract;
use App\Contracts\User\UserRoleServiceContract;
use App\Filament\Resources\StudentResource\Actions\ImportStudentsAction;
use App\Models\Student;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentColumns;
use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class StudentTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentActions;
    use HasFilamentColumns;

    public ?int $classroomId = null;

    public function mount(?int $classroomId = null): void
    {
        $this->classroomId = $classroomId;
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->query($this->applyFilters(app(UserRoleServiceContract::class)->applyRoleFilter(Student::query())))
            ->emptyStateHeading(__('Нет записей'))
            ->columns(array_merge([
                /*ImageColumn::make('photo')
                    ->label(__('Фото'))
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('student_avatars', 'avatar'))
                    ->square()
                    ->size(60)
                    ->toggleable(isToggledHiddenByDefault: true),*/
                TextColumn::make('surname')
                    ->label(__('Фамилия'))
                    ->words(5)
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__('Имя'))
                    ->words(5)
                    ->searchable(),
                TextColumn::make('patronymic')
                    ->label(__('Отчество'))
                    ->words(5)
                    ->searchable(),
                TextColumn::make('classroom.classroomName')
                    ->label(__('Класс')),

            ], $this->getAdditionFields())) // Объединяем массив столбцов с дополнительными полями
            ->filters([
                SelectFilter::make('classroom_id')
                    ->label(__('Фильтр по классу'))
                    ->options(fn() => app(ClassroomServiceContract::class)->getAccessibleClassrooms()) // Получаем классы
                    ->searchable()
                    ->preload()
                    ->default(null)
            ])
            ->actions([
                Action::make('view')
                    ->label(__('Профиль'))
                    ->icon('icon-view-primary')
                    ->url(fn($record) => route('student-view', $record)) // URL на ваш маршрут
                    ->visible(fn() => auth()->user()->can('view_student'))
                    ->authorize(fn($record) => auth()->user()->can('view_student', $record)),
                $this->editAction()
                    ->url(fn($record) => route('student-edit', $record))
                    ->visible(fn() => auth()->user()->can('update_student'))
                    ->authorize(fn($record) => auth()->user()->can('update_student', $record)),

                $this->deleteAction()
                    ->visible(fn() => auth()->user()->can('delete_student'))
                    ->authorize(fn($record) => auth()->user()->can('delete_student', $record)),
            ])
            ->headerActions([
                ImportStudentsAction::make(auth()->user()->organization_id)
                    ->visible(fn() => auth()->user()->can('update_student'))
                    ->authorize(fn($record) => auth()->user()->can('update_student', $record)),

                Action::make('template')
                    ->label(__('Скачать шаблон'))
                    ->icon('icon-file-excel')
                    ->color('success')
                    ->url(asset('templates/student-import.xlsx'))
                    ->visible(fn() => auth()->user()->can('view_student'))
                    ->authorize(fn($record) => auth()->user()->can('view_student', $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    private function applyFilters(Builder $query): Builder
    {
        if ($this->classroomId) {
            $query->where('classroom_id', $this->classroomId);
        }

        return $query;
    }

    private function getAdditionFields(): array
    {
        return array_merge([
            TextColumn::make('birthday')
                ->label(__('Дата рождения'))
                ->date('d F Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('nationality.title')
                ->label(__('Национальность'))
                ->numeric()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('organization.bin')
                ->label(__('БИН организации'))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ], $this->getCreationColumns());
    }

    public function render(): View
    {
        return view('livewire.tables.student.student-table');
    }
}
