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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('text')->nullable();
            $table->json('images')->nullable();
            $table->json('videos')->nullable();
            $table->json('files')->nullable();
            $table->json('original_filenames')->nullable();
            $table->foreignId('material_type_id')->constrained()->onDelete('restrict'); // связь с таблицей material_types
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
