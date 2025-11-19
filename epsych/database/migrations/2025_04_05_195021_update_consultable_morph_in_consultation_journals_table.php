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
        Schema::table('consultation_journals', function (Blueprint $table) {
            $table->string('consultable_type')
                ->nullable()
                ->comment('Тип консультируемого (например, Student или Classroom)');

            $table->unsignedBigInteger('consultable_id')
                ->nullable()
                ->comment('ID консультируемого');

            $table->index(['consultable_type', 'consultable_id'], 'consultable_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation_journals', function (Blueprint $table) {
            $table->dropIndex('consultable_idx');
            $table->dropColumn(['consultable_type', 'consultable_id']);
        });
    }
};
