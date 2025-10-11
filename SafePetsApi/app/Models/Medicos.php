<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicos extends Model
{
     protected $fillable = [
        'id_especialidades',
        'nombre_m',
        'apellido_m',
        'edad', 
        'telefono',
    ];

    public function especialidades() {
        return $this->belongsTo(Especialidades::class, 'id_especialidades', 'id');
    }


    public function citas() {
        return $this->hasMany(Citas::class, 'id'); 
        // Un medico puede tener varias citas
    }

    
}
