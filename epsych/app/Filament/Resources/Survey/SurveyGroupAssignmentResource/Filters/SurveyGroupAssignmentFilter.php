<?php

namespace App\Filament\Resources\Survey\SurveyGroupAssignmentResource\Filters;

use App\Enums\Survey\SurveyGroupAssignmentStatusEnum;
use App\Enums\Survey\SurveyGroupAssignmentTypeEnum;
use App\Services\Student\ClassroomService;
use Exception;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class SurveyGroupAssignmentFilter
{
    /**
     * @throws Exception
     */
    public static function make(): array
    {
        return [
            SelectFilter::make('type')
                ->label(__('Тип назначения'))
                ->options(SurveyGroupAssignmentTypeEnum::class),

            SelectFilter::make('status')
                ->label(__('Статус'))
                ->options(SurveyGroupAssignmentStatusEnum::class),

            Filter::make('assigned_at')
                ->label(__('Дата назначения'))
                ->form([
                    DatePicker::make('assigned_at_from')
                        ->label(__('Дата назначения: от')),
                    DatePicker::make('assigned_at_until')
                        ->label(__('Дата назначения: по')),
                ])
                ->query(fn (Builder $query, array $data): Builder => $query
                    ->when($data['assigned_at_from'], fn ($q) => $q->whereDate('assigned_at', '>=', $data['assigned_at_from']))
                    ->when($data['assigned_at_until'], fn ($q) => $q->whereDate('assigned_at', '<=', $data['assigned_at_until']))
                ),

            SelectFilter::make('classroom_id')
                    ->label(__('Фильтр по классу'))
                    ->options(fn() => app(ClassroomService::class)->getAccessibleClassrooms())
                    ->searchable()
                    ->preload()
                    ->default(null),

            SelectFilter::make('organization.bin')
                ->label(__('БИН организации'))
                ->relationship('organization', 'bin')
                ->preload()
                ->searchable(),
        ];
    }
}
