<?php

namespace App\Livewire\Tables\Survey;

use App\Filament\Tables\Actions\SurveyReportOrganizationExportAction;
use App\Filament\Tables\Actions\SurveyReportRegionExportAction;
use App\Models\Survey\Survey;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SurveyReportTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort', 'desc')
            ->query(Survey::query())
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                SurveyReportOrganizationExportAction::make(),
                SurveyReportRegionExportAction::make(),
            ]);
    }

    public function render(): View
    {
        return view('livewire.tables.survey.survey-report-table');
    }
}
