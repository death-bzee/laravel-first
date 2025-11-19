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
        Schema::create('level_values', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->unsignedInteger('value')->comment('Числовое значение уровня'); // Обязательное поле
            $table->json('title')->comment('Название уровня');
            $table->foreignId('level_group_id')->constrained('level_groups')->cascadeOnDelete(); // Связь с группой
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_values');
    }
};
