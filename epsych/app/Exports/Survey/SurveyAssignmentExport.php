<?php

namespace App\Exports\Survey;

use App\Models\Survey\Survey;
use App\Helpers\FruitVegetableHelper;
use App\Models\Survey\SurveyAssignment;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Survey\SurveyStudentDiagnosis;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SurveyAssignmentExport implements FromQuery, WithMapping, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(protected array $selectedIds) {}

    public function query(): Builder
    {
        return SurveyAssignment::query()
            ->with(['student', 'groupAssignment'])
            ->whereIn('id', $this->selectedIds);
    }

    public function map($row): array
    {
        $fakeName = FruitVegetableHelper::getRandom();

        $surveyAssignments = SurveyAssignment::where('student_id', $row->student?->id)->where('group_id', $row->groupAssignment?->id)->first();

        // dd($surveyAssignments);
        $surveyStudentDiagnoses = SurveyStudentDiagnosis::where('survey_assignment_id', $surveyAssignments->id)->first();

        $html = $surveyStudentDiagnoses->diagnosis ?? '';

        // достаём пункты из <li>
        preg_match_all('/<li.*?>(.*?)<\/li>/si', $html, $matches);

        $items = array_map(
            fn($item) => trim(strip_tags($item)),
            $matches[1] ?? []
        );

        // склеиваем пункты через перенос строки
        $diagnosisText = implode("\n", $items);

        return [
            $row->groupAssignment?->title,
            $row->groupAssignment?->organization?->bin,
            $row->student?->id,
            $row->student->surname . ' ' . $row->student->name . ' ' . $row->student->patronymic ?? $fakeName,
            $row->assigned_at,
            $row->started_at,
            $row->completed_at,
            $diagnosisText ?? 'N/A',
        ];
    }

    public function headings(): array
    {
        return [
            'Группа',
            'Организация',
            'ID ученика',
            'ФИО',
            'Назначен',
            'Запущен',
            'Завершен',
            'Результат',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Включаем перенос текста для всех колонок
            'H' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 20,
            'D' => 40,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 100, // ширина колонки H
        ];
    }
}
