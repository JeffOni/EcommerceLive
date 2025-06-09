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
        Schema::create('cantons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 4)->unique(); // Código de 4 dígitos único
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Índice para mejorar performance en consultas por provincia
            $table->index('province_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cantons');
    }
};
