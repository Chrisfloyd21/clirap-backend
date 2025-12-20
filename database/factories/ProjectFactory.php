<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB; // <--- OBLIGATOIRE

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Infanzia', 'Salute', 'Educazione', 'Acqua', 'Emergenza'];
        
        return [
            'title_it' => $this->faker->sentence(6),
            'description_it' => $this->faker->paragraph(3),
            'category' => $this->faker->randomElement($categories),
            'image_url' => 'https://placehold.co/800x600/png?text=Progetto+Clirap',
            
            // C'EST LÀ QUE SE JOUE LA RÉUSSITE DU SEEDER 👇
            'is_completed' => DB::raw($this->faker->boolean() ? 'true' : 'false'),
        ];
    }
}