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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->json('details')->nullable();

            // NULL = nessuna coda. INT = numero massimo di utenti contemporanei.
            $table->integer('max_concurrent_users')->nullable();

            // Quanto tempo (secondi) ha l'utente per completare l'acquisto prima di essere buttato fuori.
            $table->integer('queue_timeout_seconds')->default(600);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
