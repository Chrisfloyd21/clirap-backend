<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB; // <--- TRES IMPORTANT

class PostFactory extends Factory
{
    public function definition(): array
    {
        $titles = ['Missione Compiuta', 'Nuova Scuola', 'Aiuto Umanitario', 'Grazie a tutti'];
        $chosenTitle = $this->faker->randomElement($titles);
        $content = "Ceci est un contenu de test généré automatiquement.";

        return [
            'title_it' => $chosenTitle,
            'slug' => Str::slug($chosenTitle) . '-' . rand(100, 999),
            'content_it' => $content,
            'excerpt_it' => Str::limit($content, 100),
            'image_url' => 'https://placehold.co/800x600/png?text=News+Clirap',
            
            // LA CORRECTION
            'is_published' => DB::raw('true'),
            
            'user_id' => User::factory(),
        ];
    }
}