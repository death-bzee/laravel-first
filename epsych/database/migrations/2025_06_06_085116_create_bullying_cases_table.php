<?php

use App\Enums\Bullying\BullyingCaseStatusEnum;
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
        Schema::create('bullying_cases', function (Blueprint $table) {
            $table->id();
            $table->string('victim')->comment('Потерпевший');
            $table->string('aggressor')->nullable()->comment('Агрессор');
            $table->text('description')->comment('Описание инцидента');
            $table->date('incident_date')->comment('Дата');
            $table->string('status')
                ->default(BullyingCaseStatusEnum::UnderReview->value)
                ->comment('Статус');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('set null');
            $table->foreignId('role_id')->nullable()->comment('Роль, связанная с кейсом')->constrained('roles')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bullying_cases');
    }
};
