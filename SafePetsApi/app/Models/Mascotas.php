<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascotas extends Model
{
    protected $table = 'mascotas';
    protected $primaryKey = 'id_mascotas';

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'edad',
        'sexo',
        'tamano',
        'fecha_ingreso',
        'estado_salud',
        'estado',
        'descripcion',
        'imagen',
    ];

    public function cita(){
        return $this->hasOne(Citas::class, 'id_citas', 'id_citas'); // Una relacion de uno a uno 
    }

    public function adopcion(){
        return $this->hasOne(Adopciones::class, 'id_adopciones', 'id_adopciones');
    }

    public function vacunam(){
        return $this->hasOne(VacunasMascotas::class, 'id_vacunas_mascotas', 'id_vacunas_mascotas');
    }
    
}
