<?php

namespace App\Filament\Resources\Survey\SurveyAssignmentResource\Actions;

use App\Exports\Survey\SurveyAssignmentExport;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExportSurveyAssignmentAction
{
    public static function make(): BulkAction
    {
        return BulkAction::make('exportSurveyAssignments')
            ->label('Экспорт в Excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(function (Collection $records) {
                $filename = 'survey_assignments_'.now()->format('Y-m-d_H-i-s').'.xlsx';
                $ids = $records->pluck('id')->all();

                return Excel::download(new SurveyAssignmentExport($ids), $filename);
            });
    }
}
