<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cover;
use App\Observers\CoverObserver;

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
        // Un Observer en Laravel permite escuchar eventos del modelo (created, updated, deleted, etc.)
        // y ejecutar lógica automáticamente cuando ocurren esos eventos.
        // Por ejemplo, puedes limpiar archivos, enviar notificaciones o auditar cambios.
        // Aquí registramos el observer para el modelo Cover:
        Cover::observe(CoverObserver::class);
    }
}
