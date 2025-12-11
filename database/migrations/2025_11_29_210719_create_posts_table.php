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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            
            // Titre de l'article en Italien
            $table->string('title_it');
            
            // URL conviviale (ex: /blog/il-nostro-progetto) - Important pour le SEO
            $table->string('slug')->unique();
            
            // Contenu de l'article (texte riche)
            $table->longText('content_it');
            
            // Image de couverture (optimisée/WebP)
            $table->string('image_url')->nullable();
            
            // Résumé court pour l'affichage en grille (évite de charger tout le texte)
            $table->text('excerpt_it')->nullable();
            
            // Auteur (Lien vers la table users - l'admin)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Statut : Publié ou Brouillon (permet de rédiger sans afficher)
            $table->boolean('is_published')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};