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
        Schema::create('parishes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 6)->unique(); // Código de 6 dígitos único
            $table->foreignId('canton_id')->constrained()->onDelete('cascade');
            $table->string('default_postal_code')->nullable(); // Código postal principal de la parroquia
            $table->timestamps();

            // Índice para mejorar performance en consultas por cantón
            $table->index('canton_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parishes');
    }
};
