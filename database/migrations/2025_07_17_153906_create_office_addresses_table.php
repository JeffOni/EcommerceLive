<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('office_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la oficina (ej: "Oficina Quito Centro")
            $table->string('address'); // Dirección completa
            $table->string('province'); // Provincia
            $table->string('canton'); // Cantón
            $table->string('parish'); // Parroquia
            $table->string('reference')->nullable(); // Referencia adicional
            $table->string('phone')->nullable(); // Teléfono de contacto
            $table->string('email')->nullable(); // Email de contacto
            $table->json('coordinates')->nullable(); // Coordenadas GPS {lat, lng}
            $table->string('working_hours')->nullable(); // Horarios de atención
            $table->boolean('is_main')->default(false); // Si es la oficina principal
            $table->boolean('is_active')->default(true); // Si está activa
            $table->text('notes')->nullable(); // Notas adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_addresses');
    }
};
