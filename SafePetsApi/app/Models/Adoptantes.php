<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adoptantes extends Model
{
    protected $table = 'adoptantes';
    protected $primaryKey = 'id_adoptantes';

    protected $fillable = [
        'nombre_completo',
        'cedula',
        'telefono',
        'email',
        'direccion',
    ];

    // Relaciones
    public function adopcion()
    {
        return $this->hasMany(Adopciones::class, 'id_adoptantes', 'id_adoptantes');
    }
    public function cita()
    {
        return $this->hasMany(Citas::class, 'id_adoptantes', 'id_adoptantes');
    }
}
