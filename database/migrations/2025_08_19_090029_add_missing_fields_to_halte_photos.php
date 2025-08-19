<?php
// database/migrations/2025_08_19_add_missing_fields_to_halte_photos.php

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
        Schema::table('halte_photos', function (Blueprint $table) {
            // Add missing fields that are referenced in the model
            $table->integer('file_size')->nullable()->after('description');
            $table->string('file_type')->nullable()->after('file_size');
            $table->string('original_name')->nullable()->after('file_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('halte_photos', function (Blueprint $table) {
            $table->dropColumn(['file_size', 'file_type', 'original_name']);
        });
    }
};
