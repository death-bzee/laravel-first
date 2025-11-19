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
        Schema::create('work_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->comment('Связь с пользователем')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('activity_direction_id')
                ->nullable()
                ->comment('Связь с направлением деятельности')
                ->constrained('activity_directions')
                ->nullOnDelete();

            $table->foreignId('target_group_id')
                ->nullable()
                ->comment('Связь с целевой группой')
                ->constrained('target_groups')
                ->nullOnDelete();

            $table->date('execution_deadline')->comment('Срок исполнения');
            $table->text('completion_form')->comment('Форма завершения');
            $table->text('responsible_person')->comment('Ответственные лица');
            $table->text('comment')->nullable()->comment('Комментарий');
            $table->text('execution_note')->nullable()->comment('Пометка об исполнении');

            // Полиморфная связь work_planable (nullable)
            $table->string('work_planable_type')->nullable();
            $table->unsignedBigInteger('work_planable_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_plans');
    }
};
