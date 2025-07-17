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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('custom_price', 10, 2)->nullable(); // Precio personalizado (NULL = usar precio base del producto)
            $table->integer('stock')
                ->unsigned() //no permite valores negativos
                ->default(0);
            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade'); // Elimina las variantes asociadas si se elimina el producto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
