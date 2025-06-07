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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario propietario de la dirección
            $table->integer('type'); // Tipo de dirección (casa, trabajo, etc.)
            $table->string('provincia'); // Provincia de Ecuador
            $table->string('canton'); // Cantón dentro de la provincia
            $table->string('parroquia'); // Parroquia dentro del cantón
            $table->string('reference')->nullable(); // Referencia adicional (cerca de...)
            $table->string('address'); // Dirección específica (calle, número, etc.)
            $table->integer('receiver'); // Tipo de receptor (mismo usuario, tercero, etc.)
            $table->json('receiver_info')->nullable(); // Datos del receptor (nombre, teléfono, etc.)
            $table->boolean('default')->default(false); // Si es la dirección principal del usuario

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
