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
        // 1. Création de l'Admin (si inexistant)
        $admin = User::firstOrCreate(
            ['email' => 'admin@clirap.it'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        echo "✅ Admin prêt\n";

        // 2. Création des Projets (via la Factory corrigée)
        Project::factory(10)->create();
        
        // 3. Création des Posts
        if ($admin) {
            Post::factory(10)->create(['user_id' => $admin->id]);
        }

        // 4. Messages
        Message::factory(5)->create();
        
        echo "✅ Données insérées !\n";
    }
}