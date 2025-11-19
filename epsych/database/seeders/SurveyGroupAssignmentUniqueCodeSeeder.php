<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey\SurveyGroupAssignment;
use App\Contracts\UniqueCodeServiceContract;

class SurveyGroupAssignmentUniqueCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codeService = app(UniqueCodeServiceContract::class);

        SurveyGroupAssignment::query()
            ->whereNull('unique_code')
            ->get()
            ->each(function ($item) use ($codeService) {
                $item->update([
                    'unique_code' => $codeService->generateUniqueCode(),
                ]);
            });
    }
}
