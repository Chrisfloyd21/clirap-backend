<?php

echo "<h1>🛠️ Réparation Clirap API (Mode Doux)</h1>";

// --- PARTIE 1 : NETTOYAGE CACHE ---
$filesToDelete = [
    __DIR__.'/../bootstrap/cache/config.php',
    __DIR__.'/../bootstrap/cache/routes-v7.php'
];
foreach ($filesToDelete as $file) {
    if (file_exists($file)) unlink($file);
}

// Configuration Environnement
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie');

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

try {
    echo "<h3>1. Test Connexion...</h3>";
    DB::connection()->getPdo();
    echo "<span style='color:green'>OK</span><br>";

    // MODIFICATION IMPORTANTE ICI :
    // On n'utilise plus migrate:fresh qui cause le bug de transaction.
    // On utilise migrate simple, car on a déjà vidé la base sur Neon.
    echo "<h3>2. Migration (Création des tables)...</h3>";
    
    Artisan::call('migrate', [
        '--force' => true
    ]);
    
    echo "📜 Tables créées avec succès.<br>";
    
    echo "<h3>3. Insertion des données (Seed)...</h3>";
    Artisan::call('db:seed', ['--force' => true]);
    echo "🌱 Données insérées.<br>";

    echo "<h3>4. Nettoyage final...</h3>";
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    
    echo "<hr><h1 style='color:green'>✅ FINI !</h1>";
    echo "<p>Tout est vert. Allez tester votre site !</p>";

} catch (Exception $e) {
    echo "<hr><h2 style='color:red'>❌ ERREUR</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}