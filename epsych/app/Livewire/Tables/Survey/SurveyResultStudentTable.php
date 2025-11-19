<?php

namespace App\Livewire\Tables\Survey;

use App\Models\Survey\SurveyAssignment;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class SurveyResultStudentTable extends Component implements HasForms, HasTable
{
	use InteractsWithForms;
	use InteractsWithTable;

	public int $id;

	public function table(Table $table): Table
	{
		return $table
			->emptyStateHeading(__('Нет записей'))
			->query(
				SurveyAssignment::query()
					->whereHas('group', function ($query) {
						$query->where('organization_id', auth()->user()->organization_id)
							->where('id', $this->id); // Проверяем, что переданный group_id принадлежит организации
					})
					->with(['group', 'student', 'studentDiagnosis'])
			)
			->columns([
				TextColumn::make('group.title')
					->label(__('Название теста'))
					->numeric()
					->sortable(),
				TextColumn::make('student.fullName')
					->label(__('Ученик'))
					->numeric(),
				TextColumn::make('assigned_at')
					->label(__('Создан'))
					->date('d F Y H:i')
					->sortable(),
				TextColumn::make('started_at')
					->label(__('Запущен'))
					->getStateUsing(fn($record) => $record->started_at ? date('d F Y H:i') : __('Не был запущен'))
					->sortable(),
				TextColumn::make('completed_at')
					->label(__('Завершен'))
					->getStateUsing(fn($record) => $record->completed_at ? date('d F Y H:i') : __('Не был завершен'))
					->sortable(),
				TextColumn::make('results')
					->label(__('Результаты'))
					->sortable()
					->icon('icon-view-primary')
					->color('primary')
					->getStateUsing(fn ($record) => $record->studentDiagnosis ? __('Посмотреть') : null) // Показывать "Посмотреть", если есть связанный studentDiagnosis
    				->url(fn ($record) => $record->studentDiagnosis ? route('results.view', ['id' => $record->id]) : null), // Ссылка только если есть связь
				TextColumn::make('created_at')
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
				TextColumn::make('updated_at')
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
			])
			->filters([
				//
			])
			->actions([
				//
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					//
				]),
			]);
	}

	public function render(): View
	{
		return view('livewire.tables.survey.survey-result-student-table');
	}
}
