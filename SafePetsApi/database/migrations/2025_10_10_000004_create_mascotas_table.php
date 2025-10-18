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
        Schema::create('mascotas', function (Blueprint $table) {
            $table->id('id_mascotas');
            $table->string('nombre', 50);
            $table->string('especie', 50);
            $table->string('raza', 50);
            $table->integer('edad');
            $table->enum('sexo', ['Macho', 'Hembra']);
            $table->enum('tamano', ['Pequeño', 'Mediano', 'Grande']);
            $table->date('fecha_ingreso');
            $table->string('estado_salud', 100);
            $table->enum('estado', ['Disponible', 'Adoptado', 'En Tratamiento']);
            $table->text('descripcion');
            $table->string('imagen', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascotas');
    }
};
