<?php

namespace App\Imports;

use App\Events\ImportStudentsCompleted;
use App\Helpers\ExcelDateHelper;
use App\Helpers\ExcelIinHelper;
use App\Helpers\ExcelPhoneHelper;
use App\Models\Classroom;
use App\Models\Organization;
use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;

class StudentsImport implements ShouldQueue, SkipsEmptyRows, ToModel, WithChunkReading, WithHeadingRow
{
    public function __construct(
        protected ?int $organizationId = null,
    ) {}

    public function model(array $row): ?Student
    {
        $iin = ExcelIinHelper::normalize(data_get($row, 'iin'));
        $birthdayYmd = ExcelDateHelper::normalize(data_get($row, 'birthday'));
        $phone = ExcelPhoneHelper::normalize(data_get($row, 'phone'));

        if (blank($iin) || blank($birthdayYmd)) {
            return null;
        }

        // 1) Организация
        $organizationId = $this->organizationId;
        $organizationBin = data_get($row, 'organization_bin');

        if (! $organizationId && $organizationBin) {
            $organization = Organization::query()->where('bin', $organizationBin)->first();
            $organizationId = $organization?->id;
        }

        // 2) Класс
        $gl = trim((string) data_get($row, 'grade_letter'));

        if (blank($gl) || mb_strlen($gl) < 2) {
            logger()->channel('students')->info('Пропущена строка: некорректный класс', $row);

            return null;
        }

        $grade = mb_substr($gl, 0, -1);
        $letter = mb_substr($gl, -1);

        $classroom = Classroom::query()
            ->where('grade', $grade)
            ->where('letter', $letter)
            ->first();

        // 3) Дубликаты по ИИН
        if ($this->isDuplicate($iin)) {
            logger()->channel('students')->info("Пропущена строка: дубликат ИИН {$iin}", $row);

            return null;
        }

        // 4) Создание модели
        return new Student([
            'surname' => trim((string) data_get($row, 'surname')),
            'name' => trim((string) data_get($row, 'name')),
            'patronymic' => trim((string) data_get($row, 'patronymic')),
            'iin' => $iin,
            'phone' => $phone,
            'organization_id' => $organizationId,
            'classroom_id' => $classroom?->id,
            'birthday' => $birthdayYmd,
        ]);
    }

    private function isDuplicate(string $iin): bool
    {
        return Student::query()
            ->where('iin', $iin)
            ->exists();
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function () {
                event(new ImportStudentsCompleted('Импорт успешно завершён!'));
            },
        ];
    }
}
