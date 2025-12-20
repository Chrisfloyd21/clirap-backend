<?php

use Illuminate\Support\Facades\Route;

// Au lieu d'afficher une vue complexe, on renvoie un simple JSON.
// C'est beaucoup plus sûr pour une API.
Route::get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Clirap API is running properly 🚀',
        'laravel_version' => app()->version()
    ]);
   // --- ROUTE DE CONFIGURATION ET REMPLISSAGE ---
Route::get('/install-data-force-xyz', function () {
    try {
        // 1. Nettoyage violent du cache
        Artisan::call('optimize:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        
        // 2. Création de l'Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@clirap.it'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 3. Lancement des Seeders (Remplissage des données)
        // On force l'exécution même en production
        Artisan::call('db:seed', ['--force' => true]);

        return "<h1>✅ SUCCÈS TOTAL !</h1>" .
               "<p>1. Cache vidé.</p>" .
               "<p>2. Admin créé : <b>admin@clirap.it</b> / <b>password</b></p>" .
               "<p>3. Données factices (Seeders) générées avec succès.</p>";

    } catch (\Exception $e) {
        // En cas d'erreur, on l'affiche clairement
        return "<h1>❌ ERREUR</h1><pre>" . $e->getMessage() . "</pre>";
    }
});
});

// (Gardez votre route /install-site si vous l'avez laissée, sinon c'est bon)