<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Добавление новых полей
            $table->string('surname')->after('name');
            $table->string('patronymic')->after('surname');
            $table->boolean('is_active')->default(false)->after('id');

            // Добавление внешних ключей после profile_photo_path
            $table->foreignId('organization_id')->nullable()->after('profile_photo_path')->constrained()->onDelete('restrict');
            $table->foreignId('region_id')->nullable()->after('organization_id')->constrained()->onDelete('restrict');
            $table->foreignId('district_id')->nullable()->after('region_id')->constrained()->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['region_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['is_active','surname','patronymic','organization_id', 'region_id', 'district_id']);
        });
    }
};
