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
        Schema::table('payments', function (Blueprint $table) {
            // Eliminar relación con orders si existe
            if (Schema::hasColumn('payments', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }

            // Agregar relación con usuarios y datos del carrito
            if (!Schema::hasColumn('payments', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }

            if (!Schema::hasColumn('payments', 'cart_data')) {
                $table->json('cart_data')->nullable()->after('comments');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Eliminar nuevas columnas
            if (Schema::hasColumn('payments', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('payments', 'cart_data')) {
                $table->dropColumn('cart_data');
            }

            // Restaurar order_id
            if (!Schema::hasColumn('payments', 'order_id')) {
                $table->foreignId('order_id')->after('id')->constrained()->onDelete('cascade');
            }
        });
    }
};
