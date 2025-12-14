<?php

// On charge Laravel manuellement
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// On démarre le noyau console pour avoir accès aux commandes
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

echo "<h1>🛠️ Réparation Clirap API</h1>";

try {
    // 1. FORCER LE NETTOYAGE DU CACHE (C'est ça qui répare l'erreur 404/500)
    echo "1. Nettoyage du cache (Config & Routes)... ";
    Artisan::call('optimize:clear');
    echo "<span style='color:green'>OK</span><br>";

    // 2. LANCER LA MIGRATION
    echo "2. Connexion à Neon & Création des tables... ";
    Artisan::call('migrate:fresh', ['--force' => true]);
    echo "<span style='color:green'>OK</span><br>";

    // 3. REMPLIR LES DONNÉES
    echo "3. Création Admin & Projets... ";
    Artisan::call('db:seed', ['--force' => true]);
    echo "<span style='color:green'>OK</span><br>";

    echo "<hr><h2>✅ SUCCÈS TOTAL !</h2>";
    echo "<p>Vous pouvez maintenant accéder au site.</p>";

} catch (Exception $e) {
    echo "<hr><h2>❌ ERREUR FATALE</h2>";
    echo "<pre style='background:#f8d7da; padding:10px; border:1px solid #f5c6cb; color:#721c24'>";
    echo $e->getMessage();
    echo "</pre>";
    echo "<p>Vérifiez vos variables d'environnement sur Render.</p>";
}