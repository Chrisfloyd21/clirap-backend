<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  public function boot(): void
{
    // Force la longueur des chaînes pour éviter les erreurs d'index
    Schema::defaultStringLength(191); 
    
    if($this->app->environment('production')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
}
