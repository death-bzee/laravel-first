<?php

namespace App\Contracts\Student;

interface StudentPdfServiceContract
{
    public function generateStudentPdf(int $id, string $type);
}
