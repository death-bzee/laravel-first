<?php

namespace App\Livewire\Content\Psycholog;

use App\Filament\Infolists\Components\FileUploaded;
use App\Models\ConsultationJournal;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Illuminate\View\View;
use Livewire\Component;

class ConsultationJournalContent extends Component implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public ?ConsultationJournal $consultationJournal = null;

    public function mount(ConsultationJournal $record): void
    {
        $this->consultationJournal = $record;
    }

    public function consultationJournalInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->consultationJournal)
            ->schema([
                Section::make(__('Основная информация'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('date')
                                ->label(__('Дата'))
                                ->date(),

                            TextEntry::make('student.fullName')
                                ->label(__('Консультируемый'))
                                ->formatStateUsing(fn($record) => $record->student?->fullName .
                                    ($record->student?->classroom ? ' (' . $record->student->classroom->classroomName . ')' : '')
                                ),
                        ]),
                    ]),

                Section::make(__('Запрос'))
                    ->schema([
                        TextEntry::make('request')
                            ->label(__('Описание запроса'))
                            ->columnSpanFull(),

                        FileUploaded::make('files') // Используем универсальный FileUploaded
                        ->label(__('Прилагаемые документы'))
                            ->setCollectionName('psycholog_request_documents'), // Передаем нужную коллекцию
                    ]),

                Section::make(__('Дополнительная информация'))
                    ->schema([
                        Grid::make(2)->schema([
                            TextEntry::make('recommendations')
                                ->label(__('Рекомендации'))
                                ->columnSpan(1),

                            TextEntry::make('notes')
                                ->label(__('Примечания'))
                                ->columnSpan(1),
                        ]),

                        TextEntry::make('consultant')
                            ->label(__('Консультант'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }


    public function render(): View
    {
        return view('livewire.content.psycholog.consultation-journal-content');
    }
}
