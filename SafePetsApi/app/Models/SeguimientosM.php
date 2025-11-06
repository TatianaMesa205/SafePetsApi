<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientosMascotas extends Model
{
    protected $table = 'seguimientos_mascotas';
    protected $primaryKey = 'id_seguimientos';

    protected $fillable = [
        'id_adopciones',
        'fecha_visita',
        'observacion',
    ];

    // Relaciones
    public function adopcion()
    {
        return $this->belongsTo(Adopciones::class, 'id_adopciones', 'id_adopciones');
    }
}
