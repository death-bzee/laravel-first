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
        Schema::create('survey_group_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->enum('type', ['individual', 'group'])->default('individual');
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade');
            $table->foreignId('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->enum('status', ['pending', 'started', 'completed'])->default('pending');
            $table->timestamp('assigned_at')->nullable()->default(now());
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_group_assignments');
    }
};
