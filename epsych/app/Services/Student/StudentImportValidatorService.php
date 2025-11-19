<?php

namespace App\Services\Student;

use App\Helpers\ExcelDateHelper;
use App\Helpers\ExcelIinHelper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentImportValidatorService
{
    public function validate(string $filePath, ?int $organizationId = null): void
    {
        $expectedHeadings = $this->getExpectedHeadings(includeOrganizationBin: is_null($organizationId));

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $firstRow = $sheet->rangeToArray('A1:'.chr(64 + count($expectedHeadings)).'1')[0];
        $headings = array_map(fn ($cell) => trim(mb_strtolower($cell)), $firstRow);

        $this->validateHeadings($headings, $expectedHeadings);

        $columnMap = array_combine(range('A', chr(64 + count($expectedHeadings))), $expectedHeadings);

        $rows = $sheet->toArray(null, true, true, true);
        array_shift($rows);

        $seenIins = [];

        foreach (array_values($rows) as $index => $row) {
            $rowNumber = $index + 2;

            // 1. Скип пустых строк
            $allEmpty = true;
            foreach ($columnMap as $col => $field) {
                if (trim((string) ($row[$col] ?? '')) !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if ($allEmpty) {
                continue;
            }

            // 2. Сбор данных + нормализация
            $data = [];
            foreach ($columnMap as $col => $field) {
                $data[$field] = trim((string) ($row[$col] ?? ''));
            }

            $data['iin'] = ExcelIinHelper::normalize($data['iin']);
            $data['birthday'] = ExcelDateHelper::normalize($data['birthday']);

            // 3. Проверка дубликатов
            if (! empty($data['iin']) && in_array($data['iin'], $seenIins, true)) {
                throw ValidationException::withMessages([
                    'row' => ["Дубликат ИИН {$data['iin']} в строке {$rowNumber} (повтор в файле)."],
                ]);
            }
            $seenIins[] = $data['iin'];

            // 4. Проверка даты
            if ($data['birthday'] === null) {
                throw ValidationException::withMessages([
                    'row' => ["Неверный формат даты в строке {$rowNumber}. Ожидается дд.мм.гггг или Excel-дата."],
                ]);
            }

            // 5. Валидация
            $rules = [
                'surname' => 'required|string',
                'name' => 'required|string',
                'birthday' => ['required', 'date_format:Y-m-d'],
                'grade_letter' => ['required', 'string', 'regex:/^\d{1,2}\p{Cyrillic}$/u'],
                'iin' => ['required', 'digits:12'],
            ];

            if (! is_null($organizationId)) {
                $expectedBin = optional(auth()->user()->organization)->bin;

                if (! empty($data['organization_bin']) && $data['organization_bin'] !== $expectedBin) {
                    throw ValidationException::withMessages([
                        'row' => ['БИН организации в файле не соответствует вашей организации.'],
                    ]);
                }

                unset($data['organization_bin']);
            }

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'row' => ["Ошибка в строке {$rowNumber}: ".implode(', ', $validator->errors()->all())],
                ]);
            }
        }
    }

    private function validateHeadings(array $headings, array $expected): void
    {
        $differences = [];
        foreach ($expected as $i => $expectedHeading) {
            $actual = $headings[$i] ?? '[пусто]';
            if ($expectedHeading !== $actual) {
                $column = chr(65 + $i);
                $differences[] = "Колонка {$column}: ожидалось '{$expectedHeading}', получено '{$actual}'";
            }
        }

        if (! empty($differences)) {
            throw ValidationException::withMessages([
                'headings' => [
                    "Заголовки столбцов не соответствуют ожидаемым:\n".implode("\n", $differences),
                ],
            ]);
        }
    }

    private function getExpectedHeadings(bool $includeOrganizationBin = true): array
    {
        $headings = ['surname', 'name', 'patronymic', 'iin', 'phone'];

        if ($includeOrganizationBin) {
            $headings[] = 'organization_bin';
        }

        $headings[] = 'grade_letter';
        $headings[] = 'birthday';

        return $headings;
    }
}