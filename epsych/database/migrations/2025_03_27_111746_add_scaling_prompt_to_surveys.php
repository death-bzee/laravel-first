<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->text('scaling_prompt')->nullable()->after('interpretation')->comment('Промпт для генерации шкалирования');
            $table->text('interpretation_prompt')->nullable()->after('scaling_prompt')->comment('Промпт для генерации интерпретации результатов');
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['scaling_prompt', 'interpretation_prompt']);
        });
    }
};
