<?php

namespace App\Filament\Tables\Actions;

use App\Exports\Survey\SurveyReportOrganizationExport;
use App\Services\Survey\Report\SurveyReportService;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SurveyReportOrganizationExportAction extends Action
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'downloadSurveyReport')
            ->label(__('Отчёт по школе'))
            ->icon('heroicon-s-arrow-down-tray')
            ->color('primary')
            ->action(function ($record): BinaryFileResponse {
                $organizationId = auth()->user()->organization_id;
                $surveyId = $record->id;

                return Excel::download(
                    new SurveyReportOrganizationExport(
                        $organizationId,
                        $surveyId,
                        app(SurveyReportService::class)
                    ),
                    "survey_report_{$organizationId}_{$surveyId}.xlsx"
                );
            });
    }
}