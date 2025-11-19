<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('survey_student_diagnoses', function (Blueprint $table) {
            $table->json('scaling')->nullable()->after('explained_diagnosis')->comment('Шкалирование результатов теста');
            $table->foreignId('level_value_id')->nullable()->after('scaling')->comment('Группа риска')->constrained('level_values');
            $table->json('interpretation')->nullable()->after('level_value_id')->comment('Интерпретация результатов теста');
        });
    }

    public function down(): void
    {
        Schema::table('survey_student_diagnoses', function (Blueprint $table) {
            $table->dropColumn('scaling');
            $table->dropForeign(['level_value_id']);
            $table->dropColumn('level_value_id');
            $table->dropColumn('interpretation');
        });
    }
};
