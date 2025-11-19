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
        Schema::create('student_social_passports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('social_passport_id')->constrained()->cascadeOnDelete();

            // Значение статуса (универсальные поля для boolean, enum, foreignId)
            $table->boolean('value')->nullable()->comment('Значение для boolean статусов');

            // Уникальный составной ключ
            $table->unique(['student_id', 'social_passport_id'], 'unique_student_social_passport');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_social_passports');
    }
};
