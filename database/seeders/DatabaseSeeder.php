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
        // 1. Créer le compte Admin (Sécurisé avec firstOrCreate)
        // Cela évite l'erreur "Duplicate entry" si le script se relance
        $admin = User::firstOrCreate(
            ['email' => 'admin@clirap.it'], // On vérifie si cet email existe
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // Le mot de passe
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Compte Admin prêt : admin@clirap.it / password\n";

        // 2. Créer des Projets fictifs
        Project::factory(10)->create(); 
        
        // 3. Créer des Articles de Blog fictifs liés à l'admin
        // On vérifie qu'on a bien un utilisateur avant de créer les posts
        if ($admin) {
            Post::factory(10)->create(['user_id' => $admin->id]);
        }

        // 4. Créer des Messages de contact fictifs
        Message::factory(5)->create();
        
        echo "✅ Base de données remplie avec succès !\n";
    }
}