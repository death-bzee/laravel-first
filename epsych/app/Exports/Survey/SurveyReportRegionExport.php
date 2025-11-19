<?php

namespace App\Exports\Survey;

use App\Services\Survey\Report\SurveyReportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyReportRegionExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected Collection $data;

    protected Collection $districts;

    protected array $questionColumns = [];

    protected array $optionColumns = [];

    public function __construct(
        private readonly int $regionId,
        private readonly int $surveyId,
        private readonly SurveyReportService $surveyReportService,
    ) {
        $this->prepare();
    }

    /** Подготовка данных один раз при создании */
    protected function prepare(): void
    {
        $data = $this->surveyReportService->getRegionSurveyReport($this->regionId, $this->surveyId);
        $this->districts = collect($data->get('districts', []));

        // вопросы
        $this->questionColumns = $this->districts
            ->flatMap(fn ($d) => array_keys(data_get($d, 'report', collect())->toArray()))
            ->unique()
            ->values()
            ->toArray();

        // варианты по каждому вопросу (чтобы шапка и map шли по ОДНОЙ структуре)
        $this->optionColumns = [];
        foreach ($this->questionColumns as $qIndex => $question) {
            $options = $this->districts
                ->flatMap(fn ($d) => collect(data_get($d, "report.{$question}", []))->keys())
                ->unique()
                ->values()
                ->toArray();

            if (empty($options)) {
                $options = [''];
            }

            foreach ($options as $opt) {
                $this->optionColumns[] = [
                    'question_id' => $qIndex + 1,
                    'option' => (string) $opt,
                ];
            }
        }
    }

    public function collection(): Collection
    {
        return $this->districts;
    }

    /** Заголовки */
    public function headings(): array
    {
        $base = [
            __('№'),
            __('Район'),
            __('Количество школ'),
            __('Всего учеников'),
            __('Прошли тестирование'),
            __('Не прошли тестирование'),
            __('Мальчики'),
            __('Девочки'),
        ];

        $top = $base;
        $bottom = array_fill(0, count($base), '');

        foreach (range(1, count($this->questionColumns)) as $qid) {
            $opts = collect($this->optionColumns)
                ->where('question_id', $qid)
                ->pluck('option')
                ->all();

            foreach ($opts as $opt) {
                $top[] = $this->questionColumns[$qid - 1];
                $bottom[] = $opt;
            }
        }

        return [$top, $bottom];
    }

    public function map($row): array
    {
        static $i = 1;

        $stats = data_get($row, 'stats', []);
        $genders = data_get($stats, 'students_by_gender', []);
        $report = collect(data_get($row, 'report', []));

        $base = [
            $i++,
            data_get($row, 'district_title'),
            data_get($stats, 'schools', 0),
            data_get($stats, 'total_students', 0),
            data_get($stats, 'tested_students', 0),
            data_get($stats, 'not_tested_students', 0),
            data_get($genders, 'male', 0),
            data_get($genders, 'female', 0),
        ];

        // строго по optionColumns, чтобы столбцы совпадали с шапкой
        $vals = collect($this->optionColumns)->map(function ($rowDef) use ($report) {
            $question = $this->questionColumns[$rowDef['question_id'] - 1] ?? null;
            if ($question === null) {
                return 0;
            }
            // безопасный доступ: сначала берём коллекцию вариантов по вопросу,
            // затем из неё — значение по нужному варианту
            $byQuestion = collect($report->get($question, []));

            return (int) $byQuestion->get($rowDef['option'], 0);
        })->all();

        return array_merge($base, $vals);
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('1')->getFont()->setBold(true);

        $highestColumn = $sheet->getHighestColumn();
        $baseColumnsCount = 8;

        // автоширина базовых
        foreach (range('A', Coordinate::stringFromColumnIndex($baseColumnsCount)) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // строим шапку вопросов/ответов ИЗ optionColumns
        $colIndex = $baseColumnsCount + 1;
        foreach (range(1, count($this->questionColumns)) as $qid) {
            $question = $this->questionColumns[$qid - 1];
            $opts = collect($this->optionColumns)->where('question_id', $qid)->pluck('option')->all();
            $opts = $opts ?: [''];

            $startCol = Coordinate::stringFromColumnIndex($colIndex);
            $endCol = Coordinate::stringFromColumnIndex($colIndex + count($opts) - 1);

            $sheet->mergeCells("{$startCol}1:{$endCol}1");
            $sheet->setCellValue("{$startCol}1", wordwrap($question, 50, "\n", true));
            $sheet->getStyle("{$startCol}1")->getAlignment()->setWrapText(true);

            foreach ($opts as $opt) {
                $col = Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue("{$col}2", $opt);
                $sheet->getColumnDimension($col)->setWidth(35);
                $colIndex++;
            }
        }

        // поворот ответов
        $startCol = Coordinate::stringFromColumnIndex($baseColumnsCount + 1);
        $sheet->getStyle("{$startCol}2:{$highestColumn}2")
            ->getAlignment()
            ->setTextRotation(90)
            ->setWrapText(true);

        // высоты строк
        $sheet->getRowDimension(1)->setRowHeight(100);
        $sheet->getRowDimension(2)->setRowHeight(90);

        return [];
    }
}
