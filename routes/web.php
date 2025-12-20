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
    // --- DÉBUT ROUTE TEMPORAIRE ---
Route::get('/setup-admin-secret-xyz', function () {
    try {
        // On vérifie si l'admin existe déjà pour éviter les doublons
        $user = User::firstOrCreate(
            ['email' => 'admin@clirap.it'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Mot de passe : password
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return "✅ Succès ! L'admin a été créé (ou existait déjà).<br>Email: admin@clirap.it<br>Password: password";
    } catch (\Exception $e) {
        return "❌ Erreur : " . $e->getMessage();
    }
});
// --- FIN ROUTE TEMPORAIRE ---
});

// (Gardez votre route /install-site si vous l'avez laissée, sinon c'est bon)