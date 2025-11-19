<?php

namespace App\Livewire\Tables\Student;

use App\Contracts\User\UserRoleServiceContract;
use App\Filament\Tables\Actions\ViewAction;
use App\Filament\Tables\Filters\TextInputFilter;
use App\Models\Classroom;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentColumns;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ClassroomTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentActions;
    use HasFilamentColumns;

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        $userService = app(UserRoleServiceContract::class);
        $organizationIds = $userService->getOrganizationsByUser();

        return $table
            ->query(Classroom::query()
            ->whereHas('students', function ($query) use ($organizationIds) {
                $query->whereIn('organization_id', $organizationIds);
            }))
            ->columns([
                TextColumn::make('grade')
                    ->label(__('Класс'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('letter')
                    ->label(__('Литера'))
                    ->searchable(),
            ])
            ->filters([
                TextInputFilter::make('grade', __('Класс')),
                TextInputFilter::make('letter', __('Литера')),
            ])
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->actions([
                ViewAction::make()
                    ->label(__('Просмотр'))
                    ->route('classroom-view'),
                ViewAction::make()
                    ->label(__('Результаты тестирования'))
                    ->route('survey.statistics'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.student.classroom-table');
    }
}
