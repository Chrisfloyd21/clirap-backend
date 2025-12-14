<?php

echo "<h1>🛠️ Réparation Clirap API (Mode Nucléaire)</h1>";

// --- PARTIE 1 : NETTOYAGE PHYSIQUE (AVANT LARAVEL) ---
// On supprime les fichiers de cache manuellement avec PHP
// C'est le seul moyen de contourner une config corrompue sans Shell
$filesToDelete = [
    __DIR__.'/../bootstrap/cache/config.php',
    __DIR__.'/../bootstrap/cache/routes-v7.php',
    __DIR__.'/../bootstrap/cache/packages.php',
    __DIR__.'/../bootstrap/cache/services.php'
];

echo "<h3>1. Suppression physique du cache...</h3>";
foreach ($filesToDelete as $file) {
    if (file_exists($file)) {
        if(unlink($file)) {
            echo "🗑️ Supprimé : " . basename($file) . "<br>";
        } else {
            echo "⚠️ Impossible de supprimer : " . basename($file) . "<br>";
        }
    } else {
        echo "⚪ Déjà absent : " . basename($file) . "<br>";
    }
}
echo "<hr>";

// --- PARTIE 2 : CONFIGURATION ENVIRONNEMENT ---
// On force l'utilisation de l'Array pour le cache le temps de la réparation
// Cela évite d'écrire dans la base de données ou les fichiers
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie');

// --- PARTIE 3 : LANCEMENT DE LARAVEL ---
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

try {
    echo "<h3>2. Vérification Connexion Database...</h3>";
    // On teste si on arrive à toucher Neon
    try {
        DB::connection()->getPdo();
        echo "<span style='color:green'>✅ Connexion PostgreSQL (Neon) réussie !</span><br>";
    } catch (\Exception $e) {
        throw new Exception("Impossible de se connecter à Neon. Vérifiez vos variables Render (DB_HOST, DB_PASSWORD...). Erreur: " . $e->getMessage());
    }

    echo "<h3>3. Migration & Seed...</h3>";
    // On lance la migration
    Artisan::call('migrate:fresh', ['--force' => true]);
    echo "📜 Tables créées.<br>";
    
    // On lance le seed
    Artisan::call('db:seed', ['--force' => true]);
    echo "🌱 Données (Admin/Projets) insérées.<br>";

    echo "<h3>4. Nettoyage final...</h3>";
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    echo "✨ Cache Laravel purgé.<br>";

    echo "<hr><h1 style='color:green'>✅ MISSION ACCOMPLIE !</h1>";
    echo "<p>Le site est réparé. Vous pouvez accéder au Frontend.</p>";

} catch (Exception $e) {
    echo "<hr><h2 style='color:red'>❌ ERREUR</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<br>Trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}