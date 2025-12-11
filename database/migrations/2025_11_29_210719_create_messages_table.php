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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Informations de l'expéditeur
            $table->string('name');
            $table->string('email');
            
            // Sujet du message (ex: "Demande de partenariat", "Donation")
            $table->string('subject')->nullable();
            
            // Le corps du message
            $table->text('message');
            
            // Statut de lecture pour le dashboard Admin (Non lu par défaut)
            $table->boolean('is_read')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};