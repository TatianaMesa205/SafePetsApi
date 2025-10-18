<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donaciones', function (Blueprint $table) {
            $table->id('id_donaciones'); // Llave primaria autoincremental
            $table->unsignedBigInteger('id_usuarios');
            $table->decimal('monto', 10, 2);
            $table->date('fecha');
            $table->enum('metodo_pago', ['Efectivo', 'Transferencia', 'Tarjeta']);

            // Clave foránea
            $table->foreign('id_usuarios')
                ->references('id_usuarios')
                ->on('usuarios')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donaciones');
    }
};
