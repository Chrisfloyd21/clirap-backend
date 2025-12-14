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
});

// (Gardez votre route /install-site si vous l'avez laissée, sinon c'est bon)