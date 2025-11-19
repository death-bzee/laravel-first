<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->string('phone')
                ->nullable()
                ->after('title')
                ->comment(__('Номер телефона'));
        });
    }

    public function down(): void
    {
        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};