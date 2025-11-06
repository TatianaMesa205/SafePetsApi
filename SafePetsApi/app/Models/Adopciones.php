<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adopciones extends Model
{
    protected $table = 'adopciones';
    protected $primaryKey = 'id_adopciones';

    protected $fillable = [
        'id_mascotas',
        'id_adoptantes',
        'fecha_adopcion',
        'estado',
        'observaciones',
        'contrato',
    ];

    // Relaciones
    public function mascota()
    {
        return $this->belongsTo(Mascotas::class, 'id_mascotas', 'id_mascotas');
    }

    public function adoptante()
    {
        return $this->belongsTo(Adoptantes::class, 'id_adoptantes', 'id_adoptantes');
    }

    public function seguimiento()
    {
        return $this->hasMany(SeguimientosMascotas::class, 'id_adopciones', 'id_adopciones');
    }
}
