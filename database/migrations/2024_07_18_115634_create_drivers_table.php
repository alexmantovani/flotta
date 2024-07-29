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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable(); // Posso legare un pilota ad un utente
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('uuid')->nullable(); // Matricola
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
