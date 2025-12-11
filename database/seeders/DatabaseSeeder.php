<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Post;
use App\Models\Message;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer le compte Admin Spécifique
        // On le met dans une variable $admin pour l'utiliser ensuite
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@clirap.it',
            'password' => bcrypt('password'), // Le mot de passe sera 'password'
        ]);

        echo "✅ Compte Admin créé : admin@clirap.it / password\n";

        // 2. Créer des Projets fictifs
        // Si vous n'avez pas encore créé la Factory, voir l'étape 2 ci-dessous
         Project::factory(10)->create(); 
        
        // 3. Créer des Articles de Blog fictifs liés à l'admin
        Post::factory(10)->create(['user_id' => $admin->id]);

        // 4. Créer des Messages de contact fictifs
        Message::factory(5)->create();
    }
}