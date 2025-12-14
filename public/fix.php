<?php

// 1. SUPPRESSION PHYSIQUE DU CACHE (Le plus important)
// On supprime tout ce qui peut brouiller Laravel
$files = [
    __DIR__.'/../bootstrap/cache/config.php',
    __DIR__.'/../bootstrap/cache/routes-v7.php',
    __DIR__.'/../bootstrap/cache/events.php',
    __DIR__.'/../bootstrap/cache/packages.php'
];

echo "<h1>🛠️ Réparation Cache & Routes</h1>";
foreach($files as $f) {
    if(file_exists($f)) {
        unlink($f);
        echo "🗑️ Cache supprimé : " . basename($f) . "<br>";
    }
}

// 2. FORCER LA CONFIGURATION LOGS
// On dit à Laravel d'écrire dans la sortie standard (console) et pas sur le disque
putenv('LOG_CHANNEL=stderr');
putenv('APP_DEBUG=true');

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

try {
    // 3. NETTOYAGE VIA ARTISAN
    echo "<h3>Nettoyage Artisan...</h3>";
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    echo "✅ Artisan cache cleared.<br>";

    // 4. LISTE DES ROUTES (DIAGNOSTIC)
    echo "<h3>🔍 Liste des Routes détectées :</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background:#eee'><th>Method</th><th>URI</th><th>Name</th></tr>";
    
    $routes = Route::getRoutes();
    $foundApi = false;

    foreach ($routes as $route) {
        echo "<tr>";
        echo "<td>" . implode('|', $route->methods()) . "</td>";
        echo "<td><b>" . $route->uri() . "</b></td>";
        echo "<td>" . $route->getName() . "</td>";
        echo "</tr>";

        if(str_contains($route->uri(), 'api/projects')) {
            $foundApi = true;
        }
    }
    echo "</table>";

    if ($foundApi) {
        echo "<h2 style='color:green'>✅ LA ROUTE API EXISTE !</h2>";
        echo "<p>Si vous la voyez dans la liste ci-dessus, le 404 devrait disparaître.</p>";
    } else {
        echo "<h2 style='color:red'>❌ PROBLÈME : ROUTE API ABSENTE</h2>";
        echo "<p>Vérifiez que votre fichier <code>routes/api.php</code> contient bien le code !</p>";
    }

} catch (Exception $e) {
    echo "<h1 style='color:red'>ERREUR CRITIQUE</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}