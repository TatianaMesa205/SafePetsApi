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
            $table->unsignedBigInteger('id_usuarios')->nullable();

            $table->string('codigo_referencia', 255)->nullable();
            $table->decimal('monto', 10, 2)->nullable();
            $table->enum('estado_pago', ['pendiente', 'aprobado', 'rechazado', 'fallido'])->default('pendiente');
            $table->string('transaccion_id_externa', 255)->nullable();
            $table->dateTime('fecha')->nullable();
            $table->string('metodo_pago', 50)->nullable();

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
