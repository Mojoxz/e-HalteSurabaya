<?php
// database/migrations/2025_01_01_000003_create_rental_histories_table.php

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
        Schema::create('rental_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('halte_id')->constrained('haltes')->onDelete('cascade');
            $table->string('rented_by');
            $table->date('rent_start_date');
            $table->date('rent_end_date');
            $table->decimal('rental_cost', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('rental_histories');
    }
};
