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
        Schema::create('seguimientos_mascotas', function (Blueprint $table) {
            $table->id('id_seguimientos'); // Llave primaria autoincremental
            $table->unsignedBigInteger('id_adopciones');
            $table->date('fecha_visita');
            $table->text('observacion');

            // Clave foránea
            $table->foreign('id_adopciones')->references('id_adopciones')->on('adopciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos_mascotas');
    }
};
