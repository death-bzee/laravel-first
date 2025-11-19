<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_results', function (Blueprint $table) {
            $table->id();

            // Внешний ключ на survey_assignments
            $table->foreignId('survey_assignment_id')
                ->constrained('survey_assignments')
                ->onDelete('cascade');

            // Внешний ключ на survey_questions
            $table->foreignId('question_id')
                ->constrained('survey_questions')
                ->onDelete('cascade');

            // Внешний ключ на survey_question_options
            $table->foreignId('option_id')
                ->constrained('survey_question_options')
                ->onDelete('cascade');

            // Полиморфные связи для выпадающих список
            $table->unsignedBigInteger('morphable_id')
                ->nullable()
                ->comment('ID связанной сущности для ответов с выпадающими списками (например, student_id, teacher_id)');
            $table->string('morphable_type')
                ->nullable()
                ->comment('Тип связанной сущности для ответов с выпадающими списками (например, App\Models\Student, App\Models\Teacher)');

            // Поле для хранения времени создания и обновления записи
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_results');
    }
};
