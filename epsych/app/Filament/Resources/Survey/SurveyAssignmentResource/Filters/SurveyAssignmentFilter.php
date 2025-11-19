<?php

namespace App\Filament\Resources\Survey\SurveyAssignmentResource\Filters;

use App\Filament\Tables\Filters\DateRangeFilter;
use App\Services\Student\ClassroomService;
use Exception;
use Filament\Tables\Filters\SelectFilter;

class SurveyAssignmentFilter
{
    /**
     * @throws Exception
     */
    public static function make(): array
    {
        return [
            /*SelectFilter::make('group.classroom_id')
                ->label(__('Фильтр по классу'))
                ->options(fn () => app(ClassroomService::class)->getAccessibleClassrooms())
                ->searchable()
                ->preload(),*/

            /*SelectFilter::make('group.organization.bin')
                ->label(__('БИН организации'))
                ->relationship('group.organization', 'bin')
                ->preload()
                ->searchable(),*/

/*            DateRangeFilter::make('assigned_at', 'Назначен'),
            DateRangeFilter::make('started_at', 'Запущен'),*/
            DateRangeFilter::make('completed_at', 'Завершен'),
        ];
    }
}
