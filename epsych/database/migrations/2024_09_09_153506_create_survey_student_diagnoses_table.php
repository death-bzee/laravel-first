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
        Schema::create('survey_student_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_assignment_id');
            $table->json('diagnosis'); // Поле для хранения диагноза
            $table->json('explained_diagnosis')->nullable(); // Поле для хранения обьяснение диагноза
            $table->timestamps();

            // Внешний ключ на таблицу SurveyAssignments
            $table->foreign('survey_assignment_id')->references('id')->on('survey_assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_student_diagnoses');
    }
};
