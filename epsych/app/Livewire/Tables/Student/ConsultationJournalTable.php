<?php

namespace App\Livewire\Tables\Student;

use App\Models\ConsultationJournal;
use App\Models\Student;
use App\Traits\Filament\HasFilamentColumns;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ConsultationJournalTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentColumns;

    public int $studentId;

    public function mount(int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ConsultationJournal::query()
                ->with('media')
                ->where('consultable_type', Student::class)
                ->where('consultable_id', $this->studentId))
            ->columns([
                TextColumn::make('date')
                    ->label(__('Дата'))
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('consultant')
                    ->label(__('Консультант'))
                    ->searchable(),
                TextColumn::make('consultant')
                    ->label(__('Консультант'))
                    ->searchable(),
                TextColumn::make('document_download')
                    ->label(__('Документ'))
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('primary')
                    ->iconColor('primary')
                    ->state(function ($record) {
                        return $record
                            ->getFirstMedia('psycholog_request_documents')
                            ?->name; // используем кастомное поле name
                    })
                    ->url(fn($record) => $record->getFirstMediaUrl('psycholog_request_documents'))
                    ->openUrlInNewTab(),
                ...self::getCreationColumns(),
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
        return view('livewire.tables.student.consultation-journal-table');
    }
}
