<?php
echo "<h1>🛠️ Réparation Clirap (Mode Remplissage Seul)</h1>";

// Nettoyage cache fichiers
$files = [__DIR__.'/../bootstrap/cache/config.php', __DIR__.'/../bootstrap/cache/routes-v7.php'];
foreach($files as $f) if(file_exists($f)) unlink($f);

// Config env
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=cookie');

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

try {
    echo "<h3>1. Vérification Tables...</h3>";
    // On vérifie si la table users existe bien (grâce à votre SQL manuel)
    try {
        DB::table('users')->count();
        echo "<span style='color:green'>✅ Tables détectées sur Neon !</span><br>";
    } catch(Exception $e) {
        throw new Exception("Les tables n'existent pas. Avez-vous lancé le code SQL sur Neon ?");
    }

    echo "<h3>2. Remplissage des données (Seed)...</h3>";
    // On lance UNIQUEMENT le remplissage (plus de migration)
    Artisan::call('db:seed', ['--force' => true]);
    echo "🌱 Admin et Projets créés avec succès.<br>";

    echo "<h3>3. Nettoyage final...</h3>";
    Artisan::call('config:clear');
    
    echo "<hr><h1 style='color:green'>✅ SITE OPÉRATIONNEL !</h1>";
    echo "<p>Vous pouvez aller sur : <br>";
    echo "Frontend: <b>" . env('FRONTEND_URL') . "</b><br>";
    echo "Login: <b>admin@clirap.it</b> / <b>password</b></p>";

} catch (Exception $e) {
    echo "<hr><h2 style='color:red'>❌ ERREUR</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}