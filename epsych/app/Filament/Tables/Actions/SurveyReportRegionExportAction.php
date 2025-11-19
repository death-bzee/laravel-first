<?php

namespace App\Filament\Tables\Actions;

use App\Exports\Survey\SurveyReportRegionExport;
use App\Services\Survey\Report\SurveyReportService;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SurveyReportRegionExportAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'downloadRegionSurveyReport')
            ->label(__('Отчёт по региону'))
            ->icon('heroicon-s-arrow-down-tray')
            ->color('success')
            ->action(function ($record): BinaryFileResponse {
                $regionId = auth()->user()->region_id;
                $surveyId = $record->id;

                return Excel::download(
                    new SurveyReportRegionExport(
                        $regionId,
                        $surveyId,
                        app(SurveyReportService::class),
                    ),
                    "survey_region_report_{$regionId}_{$surveyId}.xlsx"
                );
            })
			->visible(fn () => auth()->user()->hasRole('correctional_service_region'));
    }
}
