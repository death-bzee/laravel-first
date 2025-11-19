<?php

namespace App\Livewire\Tables\Bullying;

use App\Contracts\User\UserRoleServiceContract;
use App\Enums\Bullying\BullyingCaseStatusEnum;
use App\Enums\RoleEnum;
use App\Filament\Resources\Bullying\BullyingCaseResource\Filters\IncidentDateFilter;
use App\Filament\Resources\Bullying\BullyingCaseResource\Infolists\BullyingCaseInfoList;
use App\Filament\Tables\Filters\OrganizationFilter;
use App\Models\Bullying\BullyingCase;
use App\Traits\Filament\HasFilamentColumns;
use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class BullyingCaseTable extends Component implements HasForms, HasTable
{
    use HasFilamentColumns;
    use InteractsWithForms;
    use InteractsWithTable;

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        $organizationIds = app(UserRoleServiceContract::class)->getOrganizationsByUser();

        $roleId = Role::where('name', auth()->user()->getRoleNames()->first())->value('id');

        return $table
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('Нет записей'))
            ->query(
                BullyingCase::query()
                    ->whereIn('organization_id', $organizationIds)
                    ->where('role_id', $roleId)
            )
            ->columns([
                TextColumn::make('victim')
                    ->label(__('Потерпевший'))
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('aggressor')
                    ->label(__('Агрессор'))
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('incident_date')
                    ->label(__('Дата инцидента'))
                    ->date('d F Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('Статус'))
                    ->badge()
                    ->formatStateUsing(fn (BullyingCaseStatusEnum $state) => $state->getLabel())
                    ->color(fn (BullyingCaseStatusEnum $state) => $state->getColor())
                    ->searchable(),

                TextColumn::make('organization.title')
                    ->label(__('Организация'))
                    ->visible(function (): bool {
                        $user = auth()->user();

                        return $user->hasRole([
                            RoleEnum::CorrectionalServiceDistrict->value,
                            RoleEnum::CorrectionalServiceRegion->value,
                        ]);
                    }),

                ...self::getCreationColumns(),
            ])
            ->filters([
                IncidentDateFilter::make(),
                OrganizationFilter::make($organizationIds),
            ])
            ->actions([
                EditAction::make()
                    ->label(__('Изменить статус'))
                    ->color('warning')
                    ->modalHeading(__('Изменить статус заявки'))
                    ->form([
                        Select::make('status')
                            ->label(__('Статус'))
                            ->options(BullyingCaseStatusEnum::class),
                    ]),
                ViewAction::make()
                    ->label(__('Подробнее'))
                    ->modalHeading(__('Просмотр случая буллинга'))
                    ->color('primary')
                    ->infolist(BullyingCaseInfoList::make()),
            ]);
    }

    public function viewRecord($record): Infolist
    {
        return Infolist::make()
            ->record($record)
            ->schema([
                TextEntry::make('victim')->label(__('Потерпевший')),
                TextEntry::make('aggressor')->label(__('Агрессор')),
                TextEntry::make('incident_date')
                    ->label(__('Дата инцидента'))
                    ->date('d F Y'),
                TextEntry::make('status')
                    ->label(__('Статус'))
                    ->formatStateUsing(fn ($state) => $record->status->getLabel()),
                TextEntry::make('organization.title')
                    ->label(__('Организация')),
                TextEntry::make('role.name')
                    ->label(__('Роль')),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.bullying.bullying-case-table');
    }
}
