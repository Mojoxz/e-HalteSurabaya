<?php
// database/migrations/2025_01_02_000001_create_halte_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('halte_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('halte_id')->constrained('haltes')->onDelete('cascade');
            $table->string('document_type'); // 'simbada' or 'other'
            $table->string('document_name');
            $table->string('document_path');
            $table->string('file_type'); // pdf, jpg, png, etc
            $table->integer('file_size'); // in bytes
            $table->text('description')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['halte_id', 'document_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('halte_documents');
    }
};
