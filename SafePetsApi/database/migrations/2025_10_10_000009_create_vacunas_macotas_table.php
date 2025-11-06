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
        Schema::create('vacunas_mascotas', function (Blueprint $table) {
            $table->id('id_vacunas_mascotas');
            $table->unsignedBigInteger('id_mascotas');
            $table->unsignedBigInteger('id_vacunas');
            $table->date('fecha_aplicacion');
            $table->date('proxima_dosis')->nullable();

            // Relaciones
            $table->foreign('id_mascotas')->references('id_mascotas')->on('mascotas')->onDelete('cascade');
            $table->foreign('id_vacunas')->references('id_vacunas')->on('vacunas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacunas_mascotas');
    }
};
