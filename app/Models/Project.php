<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB; // <--- TRES IMPORTANT : AJOUTE CET IMPORT

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
            
            // LA SOLUTION BLINDÉE :
            // On utilise une commande SQL brute pour choisir true ou false.
            // PostgreSQL recevra littéralement "true" ou "false", pas 0 o 1.
            'is_completed' => DB::raw($this->faker->boolean() ? 'true' : 'false'),
        ];
    }
}