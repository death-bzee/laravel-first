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
        Schema::table('users', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('surname')->change();
            $table->text('patronymic')->nullable()->change();
            $table->text('email')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('surname')->change();
            $table->string('patronymic')->nullable()->change();
            $table->string('email')->change();
        });
    }
};
