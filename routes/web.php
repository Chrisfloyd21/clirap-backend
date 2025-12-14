<?php
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
    // Route temporaire pour installer le site sans Shell
Route::get('/install-site', function () {
    try {
        // 1. On nettoie le cache pour être sûr
        Artisan::call('config:clear');
        
        // 2. On lance la migration complète
        Artisan::call('migrate:fresh', ['--force' => true]);
        
        // 3. On remplit la base (Admin, Projets...)
        Artisan::call('db:seed', ['--force' => true]);
        
        return "✅ SUCCÈS ! La base de données est installée et l'admin est créé.";
    } catch (\Exception $e) {
        // En cas d'erreur, on l'affiche à l'écran
        return "❌ ERREUR : " . $e->getMessage();
    }
});
});
