<?php

namespace App\Exports\Survey;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KakSToboyObrashExcelExportClassroom implements FromArray, WithStyles, WithColumnWidths, WithTitle
{
    public function __construct(
        protected array $report,     // questions, students, totals_per_student, total_all
        protected string $title = ('Как с тобой обращаются')
    ) {}

    public function title(): string
    {
        return $this->title;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 60,
            'C' => 40,
        ];
    }

    public function array(): array
    {
        $students = $this->report['students'];
        $questions = $this->report['questions'];
        $studentCount = count($students);

        $rows = [];

        // ------------------ HEADER ------------------

        // строка: "Кол учеников | | | код/номер детей"
        $header1 = [__("Кол учеников") . ": {$studentCount}", '', ''];
        for ($i = 0; $i < $studentCount; $i++) {
            $header1[] = '';
        }
        $header1[] = __('Баллы');
        $rows[] = $header1;

        // строка: № | Вопрос | Ответ | 1 | 2 | ... | Итог
        $header2 = ['№', __('Вопрос'), __('Ответ')];
        for ($i = 1; $i <= $studentCount; $i++) {
            $header2[] = $i;
        }
        $header2[] = __('Итого');
        $rows[] = $header2;

        // ------------------ BODY ------------------

        foreach ($questions as $q) {

            $answerCount = count($q['answers']);
            $isFirstAnswer = true;

            foreach ($q['answers'] as $a) {

                $row = [];

                // QUESTION (#, text) → only once per group
                if ($isFirstAnswer) {
                    $row[] = $q['number'];
                    $row[] = $q['text'];
                    $isFirstAnswer = false;
                } else {
                    $row[] = '';
                    $row[] = '';
                }

                // answer text
                $row[] = $a['text'];

                // student marks
                foreach ($a['marks'] as $mark) {
                    $row[] = $mark === '1' ? 1 : '';
                }

                // total per answer
                $row[] = $a['total'];

                $rows[] = $row;
            }
        }

        // ------------------ TOTAL ROW ------------------

        $totalRow = [__('Итого'), '', ''];

        foreach ($this->report['totals_per_student'] as $stTotal) {
            $totalRow[] = $stTotal;
        }

        $totalRow[] = $this->report['total_all'];

        $rows[] = $totalRow;

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $maxRow = $sheet->getHighestRow();
        $maxCol = $sheet->getHighestColumn();

        // рамки таблицы
        $sheet->getStyle("A1:{$maxCol}{$maxRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // жирные заголовки
        $sheet->getStyle("A1:{$maxCol}2")->getFont()->setBold(true);

        // -------------------------------
        // ОБЪЕДИНЁННЫЕ ЗАГОЛОВКИ
        // -------------------------------

        // 1) "Кол учеников"
        $sheet->mergeCells("A1:C1");
        $studentCount = count($this->report['students']);
        $sheet->setCellValue('A1', __('Кол учеников') . ": {$studentCount}");

        // 2) "Код / номер ребёнка"
        $studentCount = count($this->report['students']);

        // D = 4-й столбец → 3 + 1
        $startIndex = 4;
        $endIndex   = 3 + $studentCount;

        $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($startIndex);
        $endCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($endIndex);

        $sheet->mergeCells("{$startCol}1:{$endCol}1");
        $sheet->setCellValue("{$startCol}1", __("Код / номер ребёнка"));

        // 3) "Баллы"
        $lastIndex = $endIndex + 1;
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastIndex);
        $sheet->setCellValue("{$lastCol}1", __("Баллы"));

        // выравнивание
        $sheet->getStyle("A1:{$maxCol}{$maxRow}")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        // ---------------------------------------
        // ОБЪЕДИНЕНИЕ ВОПРОСОВ И НОМЕРОВ
        // ---------------------------------------

        $questions = $this->report['questions'];
        $row = 3; // данные начинаются со строки 3

        foreach ($questions as $q) {

            $answersCount = count($q['answers']);
            $rowspan = $answersCount;

            if ($rowspan > 1) {

                // объединяем №
                $sheet->mergeCells("A{$row}:A" . ($row + $rowspan - 1));

                // объединяем текст вопроса
                $sheet->mergeCells("B{$row}:B" . ($row + $rowspan - 1));
            }

            // выравнивание по центру
            $sheet->getStyle("A{$row}:A" . ($row + $rowspan - 1))
                ->getAlignment()
                ->setHorizontal('center')
                ->setVertical('center');

            $sheet->getStyle("B{$row}:B" . ($row + $rowspan - 1))
                ->getAlignment()
                ->setHorizontal('center')
                ->setVertical('center');

            $row += $rowspan;
        }


        return [];
    }
}
