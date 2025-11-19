<?php

namespace App\Exports\Survey;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KakSToboyObrashExcelExportOrganization implements FromArray, WithStyles, WithColumnWidths, WithTitle
{
    public function __construct(
        protected array $report,
        protected string $title = 'Как с тобой обращаются (Школа)'
    ) {}

    public function title(): string
    {
        return $this->title;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 55,
            'C' => 45,
        ];
    }

    public function array(): array
    {
        $questions  = $this->report['questions'];
        $classrooms = $this->report['classrooms'];

        $rows = [];

        // ------------------------------------------------------
        // 1. Строка 1 — только под объединение + “Барлығы”
        // ------------------------------------------------------

        // A B C — пустые
        $header1 = ['', '', ''];

        // колонки классов — тоже пустые (потом будет merge)
        foreach ($classrooms as $cl) {
            $header1[] = '';
        }

        // последняя колонка
        $header1[] = __('Итого');

        $rows[] = $header1;

        // ------------------------------------------------------
        // 2. Строка 2 — реальные названия колонок
        // ------------------------------------------------------

        $header2 = ['№', __('Вопрос'), __('Ответ')];

        foreach ($classrooms as $cl) {
            $header2[] = $cl->grade . $cl->letter;
        }

        $header2[] = __('Итого');

        $rows[] = $header2;

        // ------------------------------------------------------
        // 3. Основная таблица
        // ------------------------------------------------------

        foreach ($questions as $q) {

            $answers = $q['answers'];
            $isFirst = true;

            foreach ($answers as $ans) {

                $row = [];

                if ($isFirst) {
                    $row[] = $q['number'];
                    $row[] = $q['text'];
                    $isFirst = false;
                } else {
                    $row[] = '';
                    $row[] = '';
                }

                // вариант
                $row[] = $ans['text'];

                // данные по классам
                foreach ($ans['marks'] as $m) {
                    $row[] = $m;
                }

                // итог
                $row[] = $ans['total'];

                $rows[] = $row;
            }
        }

        return $rows;
    }


    public function styles(Worksheet $sheet)
    {
        $maxRow = $sheet->getHighestRow();
        $maxCol = $sheet->getHighestColumn();

        // рамки
        $sheet->getStyle("A1:{$maxCol}{$maxRow}")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]);

        // жирные заголовки
        $sheet->getStyle("A1:{$maxCol}2")->getFont()->setBold(true);

        // перенос текста
        $sheet->getStyle("B1:B{$maxRow}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C1:C{$maxRow}")->getAlignment()->setWrapText(true);

        // выравнивание
        $sheet->getStyle("A1:{$maxCol}{$maxRow}")
            ->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        // -----------------------------
        // 1. Объединяем “Сыныптар”
        // -----------------------------
        $classrooms = $this->report['classrooms'];

        $start = 4;                        // колонка D
        $end   = 3 + count($classrooms);  // последний класс

        $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($start);
        $endCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($end);

        $sheet->mergeCells("{$startCol}1:{$endCol}1");
        $sheet->setCellValue("{$startCol}1", __("Классы"));

        // -----------------------------
        // 2. Объединяем Барлығы (итог)
        // -----------------------------
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($end + 1);
        $sheet->mergeCells("{$lastCol}1:{$lastCol}2");
        $sheet->setCellValue("{$lastCol}1",  __("Итого"));

        // -----------------------------
        // 3. Объединение вопросов
        // -----------------------------
        $row = 3;

        foreach ($this->report['questions'] as $q) {
            $rowspan = count($q['answers']);
            if ($rowspan > 1) {
                $sheet->mergeCells("A{$row}:A" . ($row + $rowspan - 1));
                $sheet->mergeCells("B{$row}:B" . ($row + $rowspan - 1));
            }
            $row += $rowspan;
        }

        return [];
    }
}
