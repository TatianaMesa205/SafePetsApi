<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
     protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'telefono',
        'email',
        'fecha_nacimiento',
        'direccion',
    ];

    public function citas(){
        return $this->hasMany(Citas::class, 'id_citas'); // hasMany significa de uno a muchos
    }
    
}
