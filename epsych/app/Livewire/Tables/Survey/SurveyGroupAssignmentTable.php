<?php

namespace App\Livewire\Tables\Survey;

use App\Contracts\User\UserRoleServiceContract;
use App\Enums\Survey\SurveyGroupAssignmentStatusEnum;
use App\Enums\Survey\SurveyGroupAssignmentTypeEnum;
use App\Filament\Resources\Survey\SurveyGroupAssignmentResource\Filters\SurveyGroupAssignmentFilter;
use App\Filament\Tables\Actions\SurveyReportOrganizationExportAction;
use App\Livewire\Tables\Survey\Actions\SurveyGroupAssignmentTableAction;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyGroupAssignment;
use App\Services\QrCodeService;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentColumns;
use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class SurveyGroupAssignmentTable extends Component implements HasForms, HasTable
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
            ->emptyStateHeading(__('Нет записей'))
            ->defaultSort('created_at', 'desc')
            ->query(
                SurveyGroupAssignment::query()
                    ->whereIn('organization_id', $organizationIds)
                    ->with(['organization', 'classroom', 'survey'])
            )
            ->columns([
                TextColumn::make('title')
                    ->label(__('Название'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->title)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('survey.title')
                    ->label(__('Тест'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->title),

                TextColumn::make('organization.bin')
                    ->label(__('Организация'))
                    ->searchable(),

                TextColumn::make('classroom.grade')
                    ->label(__('Класс'))
                    ->sortable(),
                TextColumn::make('classroom.letter')
                    ->label(__('Литера'))
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('Тип теста'))
                    ->formatStateUsing(fn(SurveyGroupAssignmentTypeEnum $state) => $state->getLabel())
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->label(__('Статус'))
                    ->formatStateUsing(fn(SurveyGroupAssignmentStatusEnum $state) => $state->getLabel()),

                TextColumn::make('assigned_at')
                    ->label(__('Назначен'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
                TextColumn::make('started_at')
                    ->label(__('Запущен'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),

                ...self::getCreationColumns(),
            ])
            ->filters(SurveyGroupAssignmentFilter::make())
            ->actions([
                SurveyGroupAssignmentTableAction::start(),

                SurveyGroupAssignmentTableAction::generateQrImage(),

                SurveyGroupAssignmentTableAction::shortCode(),

                $this->editAction()
                    ->url(fn($record) => route('survey-group-assign-edit', $record))
                    ->visible(fn() => auth()->user()->can('update_survey::survey::group::assignment'))
                    ->authorize(fn($record) => auth()->user()->can('update_survey::survey::group::assignment', $record)),

                $this->deleteAction()
                    ->modalDescription(__('При удалении записи, удалятся связи школьников с тестами и их результаты тестирования. Вы действительно хотите удалить запись?'))
                    ->visible(fn() => auth()->user()->can('delete_survey::survey::group::assignment'))
                    ->authorize(fn($record) => auth()->user()->can('delete_survey::survey::group::assignment', $record)),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->can('delete_any_survey::survey::group::assignment'))
                        ->authorize(fn() => auth()->user()->can('delete_any_survey::survey::group::assignment')),
                ]),
            ]);
    }

    #[On('generateQRImage')]
    public function generateQR($id)
    {
        $surveyGroupAssignment = SurveyGroupAssignment::with(['organization', 'classroom'])->findOrFail($id);

        $uuid = app(QrCodeService::class)->getUuidQrCode($surveyGroupAssignment);

        if (! $uuid) {
            return response()->json(['error' => __('Что-то пошло не так')], 404);
        }

        $qrCodeImage = app(QrCodeService::class)->generateQrCodeImage($uuid);

        if (! $qrCodeImage) {
            return response()->json(['error' => __('Что-то пошло не так')], 404);
        }

        $orgName = $surveyGroupAssignment->organization?->bin ?? 'org';
        $classroomName = $surveyGroupAssignment->classroom?->classroomName ?? 'class';

        $fileName = sprintf(
            '%s_%s_%s.png',
            Str::slug($orgName),
            Str::slug($classroomName),
            $surveyGroupAssignment->title
        );

        return app(QrCodeService::class)->streamQrResponse($qrCodeImage, $fileName);
    }

    public function render(): View
    {
        return view('livewire.tables.survey.survey-group-assignment-table');
    }
}
