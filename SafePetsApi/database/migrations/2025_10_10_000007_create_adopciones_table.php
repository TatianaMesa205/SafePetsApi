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
        Schema::create('adopciones', function (Blueprint $table) {
            $table->id('id_adopciones'); // AUTO_INCREMENT y PRIMARY KEY
            $table->unsignedBigInteger('id_mascotas');
            $table->unsignedBigInteger('id_adoptantes');
            $table->date('fecha_adopcion');
            $table->enum('estado', ['Rechazado', 'Adoptado', 'En proceso']);
            $table->text('observaciones');
            $table->string('contrato', 255);

            // Claves foráneas
            $table->foreign('id_mascotas')->references('id_mascotas')->on('mascotas')->onDelete('cascade');
            $table->foreign('id_adoptantes')->references('id_adoptantes')->on('adoptantes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adopciones');
    }
};
