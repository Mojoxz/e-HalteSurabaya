<?php
// database/migrations/2025_01_02_000002_create_rental_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rental_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_history_id')->constrained('rental_histories')->onDelete('cascade');
            $table->string('document_name');
            $table->string('document_path');
            $table->string('file_type'); // pdf, jpg, png, etc
            $table->integer('file_size'); // in bytes
            $table->text('description')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('rental_history_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rental_documents');
    }
};
