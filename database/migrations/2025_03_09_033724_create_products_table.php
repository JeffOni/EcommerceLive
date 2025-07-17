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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('general_features')->nullable(); // Características generales del producto
            $table->text('recommended_preparation')->nullable(); // Preparación recomendada
            $table->string('image_path'); // Primera imagen (mantenemos compatibilidad)
            $table->string('image_2')->nullable(); // Segunda imagen
            $table->string('image_3')->nullable(); // Tercera imagen
            $table->float('price');
            $table->integer('stock')
                ->unsigned() //no permite valores negativos
                ->default(0);
            $table->foreignId('subcategory_id')
                ->constrained()
                ->onDelete('cascade'); // Elimina los productos asociados si se elimina la subcategoría
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
