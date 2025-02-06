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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vehicle_id')->nullable();
            $table->foreignId('driver_id')->nullable();
            $table->date('date');
            $table->text('note')->nullable();
            $table->string('destination')->nullable();
            $table->unsignedInteger('chilometers')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'rejected', 'maintenance'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
