<?php

namespace App\Exports\Survey;

use App\Models\Organization;
use App\Services\Survey\Report\SurveyReportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyReportOrganizationExport implements FromCollection, ShouldAutoSize, WithColumnWidths, WithStyles, WithTitle
{
    private int $organizationId;

    private int $surveyId;

    private SurveyReportService $surveyReportService;

    /**
     * Номера строк, где находятся вопросы (для жирного выделения).
     */
    private array $questionRows = [];

    public function __construct(int $organizationId, int $surveyId, SurveyReportService $surveyReportService)
    {
        $this->organizationId = $organizationId;
        $this->surveyId = $surveyId;
        $this->surveyReportService = $surveyReportService;
    }

    public function collection(): Collection
    {
        $reportData = $this->surveyReportService->getOrganizationSurveyReport(
            $this->organizationId,
            $this->surveyId
        );

        $organizationName = Organization::query()
            ->find($this->organizationId)?->title ?? __('Неизвестная организация');

        $stats = $reportData->get('stats');
        $report = $reportData->get('report');
        $surveyTitle = $reportData->get('survey_title');

        $rows = collect();

        // --- Первая строка: название организации
        $rows->push([__('Организация'), $organizationName]);

        // --- Статистика
        $rows->push([__('Всего учеников'), $stats['total_students']]);
        $rows->push([__('Ученики 5–11 классы'), $stats['students_in_grades']]);
        $rows->push([__('Прошли тестирование'), $stats['tested_students']]);
        $rows->push([__('Не прошли тестирование'), $stats['total_students'] - $stats['tested_students']]);

        // --- Разделение по полу
        foreach ($stats['students_by_gender'] as $gender => $count) {
            $rows->push([$gender, $count]);
        }

        $rows->push([__('Методика'), $surveyTitle]);
        $rows->push(['']);

        foreach ($report as $question => $options) {
            $rows->push([$question]);
            $this->questionRows[] = $rows->count();

            foreach ($options as $option => $count) {
                $rows->push([$option, $count]);
            }
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $styles = [];

        // Жирный для вопросов
        foreach ($this->questionRows as $rowNumber) {
            $styles[$rowNumber] = [
                'font' => ['bold' => true],
            ];
        }

        // Общий стиль: перенос текста и выравнивание по верхнему краю
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:B{$highestRow}")
            ->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP);

        // Подсветим строку "Методика"
        foreach ([1, 7] as $rowIndex) {
			$sheet->getStyle("A{$rowIndex}:B{$rowIndex}")
				->getFill()
				->setFillType(Fill::FILL_SOLID)
				->getStartColor()->setRGB('6366F1');

			$sheet->getStyle("A{$rowIndex}:B{$rowIndex}")
				->getFont()
				->getColor()
				->setRGB('FFFFFF');
		}

        return $styles;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40, // ширина первого столбца
            'B' => 40, // ширина второго столбца (большая для текста)
        ];
    }

    public function title(): string
    {
        return __('Отчёт по опросу');
    }
}
