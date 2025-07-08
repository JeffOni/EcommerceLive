<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cover;
use App\Models\Order;
use App\Models\Payment;
use App\Observers\CoverObserver;
use App\Observers\OrderObserver;
use App\Observers\PaymentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar el servicio de PDF de órdenes
        $this->app->singleton(\App\Services\OrderPdfService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Un Observer en Laravel permite escuchar eventos del modelo (created, updated, deleted, etc.)
        // y ejecutar lógica automáticamente cuando ocurren esos eventos.
        // Por ejemplo, puedes limpiar archivos, enviar notificaciones o auditar cambios.

        // Registrar observers para automatizar notificaciones y logs
        Cover::observe(CoverObserver::class);
        Order::observe(OrderObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
