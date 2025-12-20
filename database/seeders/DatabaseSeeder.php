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
        // 1. Créer le compte Admin (Sécurisé)
        $admin = User::firstOrCreate(
            ['email' => 'admin@clirap.it'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        echo "✅ Compte Admin prêt : admin@clirap.it / password\n";

        // 2. Créer des Projets fictifs
        // ATTENTION : Cela ne marchera que si ProjectFactory utilise DB::raw() (voir ci-dessous)
        Project::factory(10)->create(); 
        
        // 3. Créer des Articles de Blog fictifs liés à l'admin
        if ($admin) {
            Post::factory(10)->create(['user_id' => $admin->id]);
        }

        // 4. Créer des Messages de contact fictifs
        Message::factory(5)->create();
        
        echo "✅ Base de données remplie avec succès !\n";
    }
}