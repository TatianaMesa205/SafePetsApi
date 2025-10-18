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
        Schema::create('adoptantes', function (Blueprint $table) {
            $table->id('id_adoptantes'); // AUTO_INCREMENT, clave primaria
            $table->string('nombre_completo', 100);
            $table->string('cedula', 20)->unique();
            $table->string('telefono', 50);
            $table->string('email', 100);
            $table->string('direccion', 150);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoptantes');
    }
};
