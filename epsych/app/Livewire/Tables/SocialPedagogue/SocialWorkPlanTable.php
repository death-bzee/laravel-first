<?php

namespace App\Livewire\Tables\SocialPedagogue;

use App\Contracts\User\UserRoleServiceContract;
use App\Enums\RoleEnum;
use App\Enums\SocialRoleEnum;
use App\Enums\TypeFormReportEnum;
use App\Models\SocialWorkPlan;
use App\Traits\Filament\HasFilamentActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SocialWorkPlanTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasFilamentActions;

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('Нет записей'))
            ->query(app(UserRoleServiceContract::class)->applyUserFilterToQuery(SocialWorkPlan::query(), RoleEnum::SocialPedagogue))
            ->columns([
                TextColumn::make('event_title')
                    ->label(__('Мероприятие'))
                    ->sortable(),
                TextColumn::make('execution_deadline')
                    ->label(__('Срок исполнения'))
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('type_responsible_person')
                    ->label(__('Ответственные'))
                    ->formatStateUsing(fn(SocialRoleEnum $state) => $state->getLabel())
                    ->sortable(),
                TextColumn::make('type_responsible_person')
                    ->label(__('Ответственные'))
                    ->formatStateUsing(fn(SocialRoleEnum $state) => $state->getLabel())
                    ->sortable(),
                TextColumn::make('type_form_report')
                    ->label(__('Форма отчета'))
                    ->sortable()
                    ->formatStateUsing(fn(SocialWorkPlan $record) => TypeFormReportEnum::tryFrom($record->type_form_report)?->getLabel() ?? __('Неизвестно'))
                    ->icon('icon-view-primary')
                    ->color('primary')
                    ->url(fn(SocialWorkPlan $record) => match ($record->type_form_report) {
                        TypeFormReportEnum::SocialPassport->value => route('social-passport-school'),
                        TypeFormReportEnum::FileUpload->value => $record->getFirstMediaUrl('social_pedagogue_document_form_report'),
                        default => null,
                    }, true)
                    ->extraAttributes(['class' => 'link']),
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
                $this->editAction()
                    ->url(fn($record) => route('social-work-plan-edit', $record))
                    ->visible(fn() => auth()->user()->can('update_social::work::plan'))
                    ->authorize(fn($record) => auth()->user()->can('update_social::work::plan')),
                $this->deleteAction()
                    ->visible(fn() => auth()->user()->can('delete_social::work::plan'))
                    ->authorize(fn($record) => auth()->user()->can('delete_social::work::plan')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.social-pedagogue.social-work-plan-table');
    }
}
