<?php

namespace App\Exports\Survey;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Enums\Survey\SurveyReportTypeEnum;

/**
 * Оптимизированный экспорт отчётов по методикам, районам и школам
 * Автоматически использует генератор для больших массивов
 */
class SurveyReportExport implements FromGenerator, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected bool $isBigDataset;

    public function __construct(
        protected SurveyReportTypeEnum $type,
        protected array $data,
        protected array $methodics = [],
        protected array $total = [],
        protected ?string $districtTitle = null
    ) {
        // если более 10 000 строк — включаем потоковый режим
        $this->isBigDataset = count($data) > 10000;
    }

    /** Для малых наборов данных Laravel Excel всё равно поддерживает collect() */
    public function generator(): \Generator
    {
        foreach ($this->data as $row) {
            yield $row;
        }

        // добавляем строку ИТОГО только для отчёта по методикам
        if ($this->type === SurveyReportTypeEnum::METHODIC && !empty($this->total)) {
            $totals = [
                'organization_title' => 'ИТОГО:',
                'total_students' => $this->total['students'] ?? 0,
                'total_passed' => $this->total['passed'] ?? 0,
                'total_not_passed' => $this->total['not_passed'] ?? 0,
                'passed_percent' => ($this->total['students'] ?? 0) > 0
                    ? round(($this->total['passed'] / $this->total['students']) * 100, 1) . '%'
                    : '0%',
                'total_risk_students' => collect($this->data)->sum('total_risk_students') ?? 0,
            ];

            foreach ($this->methodics as $m) {
                $id = (int)$m['id'];
                $totals["methodic_{$id}_classes"] = collect($this->data)->sum("methodic_{$id}_classes");
                $totals["methodic_{$id}_students"] = collect($this->data)->sum("methodic_{$id}_students");
                $totals["methodic_{$id}_risk_students"] = collect($this->data)->sum("methodic_{$id}_risk_students");
            }

            yield (object)$totals;
        }
    }

    /** Заголовки */
    public function headings(): array
    {
        switch ($this->type) {
            case SurveyReportTypeEnum::REGION:
                return [[
                    'Район',
                    'Кол-во школ',
                    'Кол-во учащихся',
                    'Прошли',
                    'Не прошли',
                    '% прошедших',
                    ...collect(range(1, 11))->map(fn($g) => "{$g} класс")->toArray(),
                ]];

            case SurveyReportTypeEnum::DISTRICT:
                return [[
                    'Школа',
                    'Кол-во учащихся',
                    'Прошли',
                    'Не прошли',
                    'Мальчики',
                    'Девочки',
                    '% прошедших',
                    ...collect(range(1, 11))->map(fn($g) => "{$g} класс")->toArray(),
                ]];

            case SurveyReportTypeEnum::METHODIC:
                $main = ['Школа', 'Кол-во учащихся', 'Прошли', 'Не прошли', '% прошедших', 'В группе риска'];
                foreach ($this->methodics as $m) {
                    $title = is_array($m['title'])
                        ? ($m['title']['ru'] ?? reset($m['title']))
                        : $m['title'];
                    $main[] = $title;
                    $main[] = '';
                    $main[] = '';
                }

                $sub = ['', '', '', '', '', ''];
                foreach ($this->methodics as $m) {
                    $sub[] = 'Кол-во классов';
                    $sub[] = 'Кол-во детей';
                    $sub[] = 'В риске';
                }

                return [$main, $sub];

            default:
                return [['Данные']];
        }
    }

    /** Сопоставление строк */
    public function map($row): array
    {
        $get = fn($key) => (int)($row->$key ?? 0);


        switch ($this->type) {
            // 📊 Отчёт по районам
            case SurveyReportTypeEnum::REGION:
                $data = [
                    $row->district_title ?? $row['district_title'] ?? '',
                    $get('schools_count'),
                    $get('total_students'),
                    $get('passed_count'),
                    $get('not_passed_count'),
                    (($row->passed_percent ?? $row['passed_percent'] ?? 0) ?: 0) . '%',
                ];

                foreach (range(1, 11) as $grade) {
                    $total = $get('class' . $grade . '_total');
                    $passed = $get('class' . $grade . '_passed');
                    $percent = $total > 0 ? round(($passed / $total) * 100, 1) : 0;
                    $data[] = "{$passed}/{$total} ({$percent}%)";
                }
                return $data;

                // 🏫 Отчёт по школам
            case SurveyReportTypeEnum::DISTRICT:
                $data = [
                    $row->organization_title ?? $row['organization_title'] ?? '',
                    $get('total_students'),
                    $get('passed_count'),
                    $get('not_passed_count'),
                    $get('male_count'),
                    $get('female_count'),
                    (($row->passed_percent ?? $row['passed_percent'] ?? 0) ?: 0) . '%',
                ];

                foreach (range(1, 11) as $grade) {
                    $total = $get('class' . $grade . '_total');
                    $passed = $get('class' . $grade . '_passed');
                    $percent = $total > 0 ? round(($passed / $total) * 100, 1) : 0;
                    $data[] = "{$passed}/{$total} ({$percent}%)";
                }
                return $data;

                // 🧠 Отчёт по методикам
            case SurveyReportTypeEnum::METHODIC:
                $data = [
                    $row->organization_title ?? $row['organization_title'] ?? '',
                    $get('total_students'),
                    $get('total_passed'),
                    $get('total_not_passed'),
                    (($row->passed_percent ?? $row['passed_percent'] ?? 0) ?: 0) . '%',
                    $get('total_risk_students'),
                ];

                foreach ($this->methodics as $m) {
                    $id = (int)$m['id'];
                    $data[] = $get('methodic_' . $id . '_classes');
                    $data[] = $get('methodic_' . $id . '_students');
                    $data[] = $get('methodic_' . $id . '_risk_students');
                }
                return $data;

            default:
                return [];
        }
    }

    /** Стилизация шапки (только для отчёта по методикам) */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if ($this->type !== SurveyReportTypeEnum::METHODIC || empty($this->methodics)) return;

                $sheet = $event->sheet;
                $startCol = 7;

                foreach ($this->methodics as $index => $m) {
                    $colStart = $startCol + ($index * 3);
                    $colEnd = $colStart + 2;
                    $colLetterStart = Coordinate::stringFromColumnIndex($colStart);
                    $colLetterEnd = Coordinate::stringFromColumnIndex($colEnd);
                    $sheet->mergeCells("{$colLetterStart}1:{$colLetterEnd}1");
                }

                $lastCol = Coordinate::stringFromColumnIndex($startCol + count($this->methodics) * 3 - 1);

                $sheet->getStyle("A1:{$lastCol}2")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                        'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFDCE6F1'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FF999999'],
                        ],
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(28);
                $sheet->getRowDimension(2)->setRowHeight(20);

                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$lastRow}:{$lastCol}{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE2EFDA'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}
