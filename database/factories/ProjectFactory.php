<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Infanzia', 'Salute', 'Educazione', 'Acqua', 'Emergenza'];
        
        return [
            'title_it' => $this->faker->sentence(6),
            'description_it' => $this->faker->paragraph(3),
            'category' => $this->faker->randomElement($categories),
            // J'ai changé l'URL d'image pour un placeholder stable (Unsplash plante souvent en prod)
            'image_url' => 'https://placehold.co/800x600/png?text=Progetto+Clirap', 
            
            // CORRECTION CRITIQUE POUR POSTGRESQL :
            // On utilise false ou true (booléen), JAMAIS 0 ou 1.
            'is_completed' => $this->faker->boolean(20), // 20% de chance d'être true
        ];
    }
}