<?php
// database/migrations/2025_01_01_000002_create_halte_photos_table.php

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
    public function up()
    {
        Schema::create('halte_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('halte_id')->constrained('haltes')->onDelete('cascade');
            $table->string('photo_path');
            $table->string('description')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('halte_photos');
    }
};
