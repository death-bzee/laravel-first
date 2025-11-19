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
        Schema::create('prevention_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('responsible');
            $table->date('date')->nullable();
            $table->string('type');
            $table->string('status');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prevention_events');
    }
};
