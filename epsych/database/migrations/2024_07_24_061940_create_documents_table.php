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
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('file_path');
                $table->string('original_name');
                $table->bigInteger('file_size');
                $table->string('file_extension');
                $table->unsignedBigInteger('documentable_id'); // Полиморфный идентификатор
                $table->string('documentable_type'); // Полиморфный тип
                $table->foreignId('document_group_id')->constrained()->onDelete('restrict'); // Связь с группой документов
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
