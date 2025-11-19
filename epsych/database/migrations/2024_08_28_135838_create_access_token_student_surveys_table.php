<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('access_token_student_surveys', function (Blueprint $table) {
            $table->id();
            $table->string('access_code')->index(); // Индексация поля
            $table->foreignId('access_token_id')->constrained('access_tokens')->onDelete('cascade');
            $table->foreignId('survey_assignment_id')->constrained('survey_assignments')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['access_code', 'survey_assignment_id'], 'access_code_survey_assignment_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_token_student_surveys');
    }
};
