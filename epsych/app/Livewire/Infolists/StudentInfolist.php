<?php

namespace App\Livewire\Infolists;

use App\Models\Student;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class StudentInfolist extends Component implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public ?array $data = [];

    public ?Student $record;

    public function mount(Student $record): void
    {
        $this->record = $record;
    }

    public function studentInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Grid::make(3)
                    ->schema([
                        TextEntry::make('surname')->label(__('Фамилия')),
                        TextEntry::make('name')->label(__('Имя')),
                        TextEntry::make('patronymic')->label(__('Отчество')),
                    ]),

                Grid::make(3)
                    ->schema([
                        TextEntry::make('gender')->label(__('Пол'))->visible(fn($record) => filled($record?->gender)),
                        TextEntry::make('birthday')->label(__('Дата рождения')),
                        TextEntry::make('classroom.classroomName')->label(__('Класс')),
                    ]),

                Grid::make(3)
                    ->schema([
                        TextEntry::make('nationality.title')->label(__('Национальность')),
                    ])
                    ->visible(fn($record) => filled($record?->nationality)),

                Fieldset::make(__('Родитель / Законный представитель'))
                    ->schema([
                        TextEntry::make('parent.surname')->label(__('Фамилия')),
                        TextEntry::make('parent.name')->label(__('Имя')),
                        TextEntry::make('parent.patronymic')->label(__('Отчество')),
                        TextEntry::make('parent.educationLevel.title')->label(__('Образование')),
                        TextEntry::make('parent.job')->label(__('Место работы')),
                        TextEntry::make('parent.address')->label(__('Адрес')),
                        TextEntry::make('parent.phone')->label(__('Телефон')),
                    ])
                    ->columns(3)
                    ->visible(fn($record) => $record?->parent !== null),

                Section::make(__('Социальный статус'))
                    ->schema([
                        RepeatableEntry::make('studentSocialPassports')
                            ->label(__(''))
                            ->schema([
                                TextEntry::make('socialPassport.title')->label(__('')),
                                TextEntry::make('value')
                                    ->label(__(''))
                                    ->formatStateUsing(fn($state) => $state ? __('Да') : __('Нет')),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->visible(fn($record) => $record?->studentSocialPassports->isNotEmpty()),

                Grid::make(3)
                    ->schema([
                        TextEntry::make('family_size')->label(__('Состав семьи')),
                    ])
                    ->visible(fn($record) => filled($record?->family_size)),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);
    }

    public function render(): View
    {
        return view('livewire.infolists.student-infolist');
    }
}
