<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class StudentDecryptSeeder extends Seeder
{
    public function run(): void
    {
        Student::all()->each(function ($student) {
            $updated = [];

            foreach (['iin', 'phone'] as $field) {
                $rawValue = $student->getRawOriginal($field);

                if (empty($rawValue)) {
                    continue;
                }

                try {
                    $decrypted = Crypt::decryptString($rawValue);
                    $updated[$field] = $decrypted;
                } catch (DecryptException) {
                    // Некорректный payload — пропускаем
                    continue;
                }
            }

            if ($updated) {
                $student->update($updated);
            }
        });
    }
}
