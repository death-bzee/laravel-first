<?php

namespace App\Livewire\Tables;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ActivityLogTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('Нет записей'))
            ->query(
                Activity::query()
                    ->with('causer') // Загружаем пользователя заранее
                    ->when(!auth()->user()->hasRole('admin'), fn ($query) =>
                        $query->where('causer_type', User::class)
                              ->where('causer_id', auth()->id())
                    )
            )
            ->columns([
                TextColumn::make('description')
                    ->label(__('Событие'))
                    ->sortable(),

                TextColumn::make('properties.ip')
                    ->label(__('IP-адрес'))
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label(__('Дата и время'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('log_name')
                    ->label(__('Тип события'))
                    ->options([
                        'auth' => __('Пользователь вошел в систему'),
                        'logout' => __('Пользователь вышел из системы'),
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')
                            ->label(__('С'))
                            ->default(now()->startOfMonth()),

                        DatePicker::make('to')
                            ->label(__('По'))
                            ->default(now()->endOfMonth()),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query) => $query->whereDate('created_at', '>=', $data['from']))
                            ->when($data['to'], fn ($query) => $query->whereDate('created_at', '<=', $data['to']));
                    }),

            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.activity-log-table');
    }
}
