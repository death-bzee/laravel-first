<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('surname');
            $table->string('name');
            $table->string('patronymic')->nullable();
            $table->string('iin', 12)->nullable();
            $table->date('birthday');
            $table->string('phone')->nullable();
            $table->foreignId('language_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('set null');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');

            // Новые добавленные поля
            $table->enum('gender', ['male', 'female'])->nullable()->comment('Пол студента: male - мужской, female - женский');
            $table->foreignId('nationality_id')->nullable()->comment('Идентификатор национальности')->constrained('nationalities')->nullOnDelete();
            $table->integer('family_size')->nullable()->comment('Количество членов семьи');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
