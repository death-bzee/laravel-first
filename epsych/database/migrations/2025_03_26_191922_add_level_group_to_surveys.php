<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->boolean('has_level_group')
                ->default(false)
                ->comment('Есть ли связь с группой уровней')
                ->after('interpretation');

            $table->foreignId('level_group_id')
                ->nullable()
                ->comment('Группа уровней')
                ->after('has_level_group')
                ->constrained('level_groups')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn('has_level_group');
            $table->dropForeign(['level_group_id']);
            $table->dropColumn('level_group_id');
        });
    }
};
