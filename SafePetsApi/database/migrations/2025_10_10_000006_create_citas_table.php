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
        Schema::create('citas', function (Blueprint $table) {
            $table->id('id_citas'); // Llave primaria autoincremental

            // Claves foráneas
            $table->unsignedBigInteger('id_adoptantes');
            $table->unsignedBigInteger('id_mascotas');
            $table->datetime('fecha_cita');
            $table->enum('estado', ['Confirmada', 'Cancelada', 'Pendiente']);
            $table->text('motivo');

            $table->foreign('id_adoptantes')->references('id_adoptantes')->on('adoptantes')->onDelete('cascade');
            $table->foreign('id_mascotas')->references('id_mascotas')->on('mascotas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
