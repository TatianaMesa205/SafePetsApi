<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    protected $table = 'citas';
    protected $primaryKey = 'id_citas';

    protected $fillable = [
        'id_adoptantes',
        'id_mascotas',
        'fecha_cita',
        'estado',
        'motivo',
    ];

    // Relaciones
    public function adoptante()
    {
        return $this->belongsTo(Adoptantes::class, 'id_adoptantes', 'id_adoptantes');
    }

    public function mascota()
    {
        return $this->belongsTo(Mascotas::class, 'id_mascotas', 'id_mascotas');
    }
}
