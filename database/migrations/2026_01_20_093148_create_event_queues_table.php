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
        Schema::create('event_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');

            // --- GESTIONE STATO ---
            // Se NULL = In attesa (Waiting)
            // Se DATA PRESENTE = È entrato (Active)
            $table->timestamp('allowed_at')->nullable();

            // Quando scade la sessione di acquisto?
            // Se NOW() > expires_at, l'utente ha perso il posto.
            $table->timestamp('expires_at')->nullable();

            $table->timestamps(); // created_at determina la posizione in fila

            // Un utente può essere in coda per un evento solo una volta
            $table->unique(['user_id', 'event_id']);
        }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_queues');
    }
};
