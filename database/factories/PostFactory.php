<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB; // <--- OBLIGATOIRE

class PostFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'Grande successo per la cena di beneficenza',
            'Report della missione sul campo',
            'La storia di Amina',
            'Emergenza siccità',
        ];
        $chosenTitle = $this->faker->randomElement($titles);
        $content = "Contenu de test pour l'article...";

        return [
            'title_it' => $chosenTitle,
            'slug' => Str::slug($chosenTitle) . '-' . rand(100, 999),
            'content_it' => $content,
            'excerpt_it' => Str::limit($content, 100),
            'image_url' => 'https://placehold.co/800x600/png?text=News+Clirap',
            
            // C'EST LÀ QUE SE JOUE LA RÉUSSITE DU SEEDER 👇
            'is_published' => DB::raw('true'),
            
            'user_id' => User::factory(),
        ];
    }
}