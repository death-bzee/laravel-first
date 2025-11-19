<?php

namespace App\Services\Student;

use App\Contracts\Student\StudentPdfServiceContract;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentPdfService implements StudentPdfServiceContract
{
    public function generateStudentPdf(int $id, string $type)
    {
        /** @var Student $student */
        $student = Student::with(['classroom', 'language', 'organization', 'nationality', 'media'])->findOrFail($id);

        // Получение списка статусов
        $student->specialStatusesList = $student->specialStatuses()->pluck('special_statuses.title')->toArray();

        // Проверка на наличие аватара через Spatie Media Library
        $photoPath = $student->getFirstMediaPath('student_avatars') ?: public_path('images/no-photo.svg');

        if (file_exists($photoPath)) {
            $photoData = base64_encode(file_get_contents($photoPath));
            $photoMimeType = mime_content_type($photoPath);
        } else {
            $photoData = null;
            $photoMimeType = null;
        }

        // Дефолтное фото, если пользовательское фото не найдено
        $defaultPhotoPath = public_path('images/no-photo.jpg');
        $defaultPhotoData = file_exists($defaultPhotoPath) ? base64_encode(file_get_contents($defaultPhotoPath)) : null;
        $defaultPhotoMimeType = file_exists($defaultPhotoPath) ? mime_content_type($defaultPhotoPath) : 'image/jpeg';

        // Шаблон для генерации PDF на основе типа
        $view = $type === 'personal_mater_student'
            ? 'components.partials.profile.personal-mater-student'
            : 'components.partials.profile.profile-student';

        // Генерация PDF
        return Pdf::loadView($view, [
            'student' => $student,
            'photoData' => $photoData,
            'photoMimeType' => $photoMimeType,
            'defaultPhotoData' => $defaultPhotoData,
            'defaultPhotoMimeType' => $defaultPhotoMimeType,
        ])->setPaper('a4');
    }
}
