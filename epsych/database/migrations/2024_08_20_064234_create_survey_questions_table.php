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
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('images')->nullable();
            $table->enum('type', array_column(\App\Enums\Survey\SurveyQuestionTypeEnum::cases(), 'value'))->default(\App\Enums\Survey\SurveyQuestionTypeEnum::SingleChoice->value);
            $table->unsignedInteger('limited_multiple_choice')->nullable()->comment('Limit for limited_multiple_choice'); // Название поля оставлено по вашему желанию
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->integer('sort')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
