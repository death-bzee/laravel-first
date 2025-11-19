<?php

namespace App\Exports\Survey;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OlweusReportExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        protected array $classesSummary,
        protected array $testedByClass,
        protected array $reportData,
        protected string $title = 'Д. Олвеус — сводные данные по буллингу'
    ) {}

    public function collection(): Collection
    {
        $rows = collect();

        foreach ($this->classesSummary as $class) {

            $id = $class['classroom_id'];
            $name = $class['classroom_name'];

            $row = $this->reportData[$id] ?? null;

            $rows->push([
                $name,
                $class['students_count'],         // ✔ Количество учеников
                $this->testedByClass[$name] ?? 0, // ✔ Количество прошедших

                // ✔ Буллинг-данные
                $row['direct_active']['weak'] ?? 0,
                $row['direct_active']['medium'] ?? 0,
                $row['direct_active']['strong'] ?? 0,

                $row['indirect_active']['weak'] ?? 0,
                $row['indirect_active']['medium'] ?? 0,
                $row['indirect_active']['strong'] ?? 0,

                $row['direct_passive']['weak'] ?? 0,
                $row['direct_passive']['medium'] ?? 0,
                $row['direct_passive']['strong'] ?? 0,

                $row['indirect_passive']['weak'] ?? 0,
                $row['indirect_passive']['medium'] ?? 0,
                $row['indirect_passive']['strong'] ?? 0,
            ]);
        }

        // Итоги
        $rows->push([
            'Итого:',
            $rows->sum(fn($r) => $r[1]),
            $rows->sum(fn($r) => $r[2]),
            $rows->sum(fn($r) => $r[3]),
            $rows->sum(fn($r) => $r[4]),
            $rows->sum(fn($r) => $r[5]),
            $rows->sum(fn($r) => $r[6]),
            $rows->sum(fn($r) => $r[7]),
            $rows->sum(fn($r) => $r[8]),
            $rows->sum(fn($r) => $r[9]),
            $rows->sum(fn($r) => $r[10]),
            $rows->sum(fn($r) => $r[11]),
            $rows->sum(fn($r) => $r[12]),
            $rows->sum(fn($r) => $r[13]),
            $rows->sum(fn($r) => $r[14]),
        ]);

        return $rows;
    }


    public function headings(): array
    {
        return [
            [__($this->title)],
            [
                __('Школа / Класс'),
                __('Количество учеников'),
                __('Количество прошедших'),
                __('Прямой активный буллинг'),
                '',
                '',
                __('Косвенный активный буллинг'),
                '',
                '',
                __('Прямой пассивный буллинг (виктимизация)'),
                '',
                '',
                __('Косвенный пассивный буллинг (виктимизация)'),
                '',
                '',
            ],
            [
                '',
                '',
                '',
                __('Слабо выражен'),
                __('Умеренно выражен'),
                __('Ярко выражен'),
                __('Слабо выражен'),
                __('Умеренно выражен'),
                __('Ярко выражен'),
                __('Слабо выражен'),
                __('Умеренно выражен'),
                __('Ярко выражен'),
                __('Слабо выражен'),
                __('Умеренно выражен'),
                __('Ярко выражен'),
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // 1️⃣ Общие стили таблицы
        $sheet->getStyle('A1:O3')->getFont()->setBold(true);
        $sheet->getStyle('A1:O1')->getFont()->setSize(14);
        $sheet->getStyle('A1:O3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:O3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:O1')->getAlignment()->setWrapText(true);

        // 2️⃣ Объединение ячеек для заголовков
        $sheet->mergeCells('A1:O1'); // основной заголовок
        $sheet->mergeCells('A2:A3'); // Школа
        $sheet->mergeCells('B2:B3'); // Кол-во учеников
        $sheet->mergeCells('C2:C3'); // Кол-во прошедших

        $sheet->mergeCells('D2:F2');
        $sheet->mergeCells('G2:I2');
        $sheet->mergeCells('J2:L2');
        $sheet->mergeCells('M2:O2');

        // 3️⃣ Границы
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:O{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // 4️⃣ Фон для шапки
        $sheet->getStyle('A2:O3')->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFEFEFEF');

        // 5️⃣ "Итого" жирным
        $sheet->getStyle("A{$lastRow}:O{$lastRow}")->getFont()->setBold(true);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Отчёт Олвеуса';
    }
}
