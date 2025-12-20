<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB; // <--- TRES IMPORTANT : AJOUTE CET IMPORT

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

        return [
            'title_it' => $chosenTitle,
            'slug' => Str::slug($chosenTitle) . '-' . rand(100, 999),
            'content_it' => "Contenu de test...",
            'excerpt_it' => "Extrait de test...",
            'image_url' => 'https://placehold.co/800x600/png?text=News+Clirap',
            
            // LA SOLUTION BLINDÉE :
            'is_published' => DB::raw('true'), 
            
            'user_id' => User::factory(),
        ];
    }
}