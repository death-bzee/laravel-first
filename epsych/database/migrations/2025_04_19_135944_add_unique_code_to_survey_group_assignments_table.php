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
        Schema::table('survey_group_assignments', function (Blueprint $table) {
            $table->string('unique_code')->nullable()->after('survey_id')->comment('Уникальный код для QR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_group_assignments', function (Blueprint $table) {
            $table->dropColumn('unique_code');
        });
    }
};
