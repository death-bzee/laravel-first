<?php

namespace App\Livewire\Tables\Bullying;

use App\Contracts\User\UserRoleServiceContract;
use App\Enums\Bullying\PreventionEventStatusEnum;
use App\Enums\Bullying\PreventionEventTypeEnum;
use App\Enums\SocialRoleEnum;
use App\Models\Bullying\PreventionEvent;
use App\Traits\Filament\HasFilamentActions;
use App\Traits\Filament\HasFilamentColumns;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PreventionEventTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentColumns;
    use HasFilamentActions;

    public function table(Table $table): Table
    {
        $organizationIds = app(UserRoleServiceContract::class)->getOrganizationsByUser();

        return $table
            ->emptyStateHeading(__('Нет записей'))
            ->query(
                PreventionEvent::query()
                    ->whereIn('organization_id', $organizationIds)
            )
            ->columns([
                TextColumn::make('title')
                    ->label(__('Название мероприятия'))
                    ->searchable(),

                TextColumn::make('responsible')
                    ->label(__('Ответственный'))
                    ->formatStateUsing(fn (SocialRoleEnum $state) => $state->getLabel())
                    ->sortable(),

                TextColumn::make('date')
                    ->label(__('Дата'))
                    ->date()
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('Тип'))
                    ->formatStateUsing(fn (PreventionEventTypeEnum $state) => $state->getLabel())
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('Статус'))
                    ->formatStateUsing(fn (PreventionEventStatusEnum $state) => $state->getLabel())
                    ->sortable(),

                ...self::getCreationColumns(),
            ])
            ->filters([
                //
            ])
            ->actions([
                $this->editAction()
                    ->url(fn($record) => route('career-orientation-document-edit', $record))
                    ->visible(fn() => auth()->user()->can('update_bullying::prevention::event'))
                    ->authorize(fn($record) => auth()->user()->can('update_bullying::prevention::event')),
                $this->deleteAction()
                    ->visible(fn() => auth()->user()->can('delete_bullying::prevention::event'))
                    ->authorize(fn($record) => auth()->user()->can('delete_bullying::prevention::event')),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.bullying.prevention-event-table');
    }
}
