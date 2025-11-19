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
        Schema::create('social_work_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->comment('Связь с пользователем')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('event_title')->comment('Мероприятие');
            $table->date('execution_deadline')->comment('Срок исполнения');
            $table->string('type_responsible_person')->comment('Ответственные лица');
            $table->string('type_form_report')->comment('Тип формы отчета');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_work_plans');
    }
};
