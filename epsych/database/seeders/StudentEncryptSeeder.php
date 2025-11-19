<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class StudentEncryptSeeder extends Seeder
{
    public function run(): void
    {
        $students = DB::table('students')->get();

        foreach ($students as $student) {
            $updates = [];

            if (!empty($student->iin)) {
                $updates['iin'] = Crypt::encryptString($student->iin);
            }

            if (!empty($student->phone)) {
                $updates['phone'] = Crypt::encryptString($student->phone);
            }

            if (!empty($updates)) {
                DB::table('students')->where('id', $student->id)->update($updates);
            }
        }
    }
}
