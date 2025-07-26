<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // No necesitamos cambiar la estructura de la tabla,
        // solo actualizar los datos existentes para que sean compatibles con el enum

        // Los valores ya están correctos (1-8), solo necesitamos asegurar
        // que no hay valores nulos o inválidos
        DB::table('orders')
            ->whereNull('status')
            ->orWhere('status', '<', 1)
            ->orWhere('status', '>', 8)
            ->update(['status' => 1]); // PENDIENTE por defecto
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay nada que revertir
    }
};
