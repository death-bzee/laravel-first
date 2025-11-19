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
        Schema::create('consultation_journals', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('Дата консультации'); // Дата

            $table->foreignId('user_id')->comment('Связь с моделью Student')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('student_id')
                ->nullable()
                ->comment('Связь с моделью Student')
                ->constrained()
                ->nullOnDelete();

            $table->text('request')->comment('Запрос, с которым обратился студент');
            $table->text('recommendations')->comment('Рекомендации, выданные в ходе консультации');
            $table->text('notes')->nullable()->comment('Дополнительные примечания');
            $table->string('consultant')->comment('Имя консультанта, проводившего консультацию');
            $table->text('comment')->nullable()->comment('Комментарий от зам директора');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_journals');
    }
};
